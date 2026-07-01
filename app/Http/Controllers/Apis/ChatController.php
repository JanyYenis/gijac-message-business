<?php

namespace App\Http\Controllers\Apis;

use App\Exceptions\ErrorException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use App\Models\ConfiguracionMeta;
use App\Models\Contacto;
use App\Models\Mensaje;
use App\Models\Usuario;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Usuario::where('uuid', $request->user()->uuid)->first();
        $config = ConfiguracionMeta::where('cod_empresa', $usuario?->empresa?->id)->firstOrFail();
        $phoneNumberId = $config->phone_number_id;

        $usuarios = Contacto::selectRaw('
                contactos.id,
                contactos.nombre,
                contactos.apellido,
                contactos.codigo_telefono,
                contactos.telefono,
                MAX(mensajes.created_at) as fecha,
                COUNT(CASE
                    WHEN mensajes.estado IN (?, ?)
                    AND mensajes.wa_to = ?
                    THEN 1 ELSE NULL
                END) as mensajes_no_leidos,
                (SELECT body FROM mensajes
                WHERE (CONCAT(contactos.codigo_telefono, contactos.telefono) = mensajes.wa_from AND mensajes.wa_to = ?)
                    OR (CONCAT(contactos.codigo_telefono, contactos.telefono) = mensajes.wa_to AND mensajes.wa_from = ?)
                ORDER BY mensajes.created_at DESC LIMIT 1
                ) as mensaje,
                (SELECT type FROM mensajes
                WHERE (CONCAT(contactos.codigo_telefono, contactos.telefono) = mensajes.wa_from AND mensajes.wa_to = ?)
                    OR (CONCAT(contactos.codigo_telefono, contactos.telefono) = mensajes.wa_to AND mensajes.wa_from = ?)
                ORDER BY mensajes.created_at DESC LIMIT 1
                ) as tipo_mensaje',
            [
                Mensaje::ENTREGADO,
                Mensaje::ENVIADO,
                $phoneNumberId,
                $phoneNumberId,
                $phoneNumberId,
                $phoneNumberId,
                $phoneNumberId
            ])
            ->leftJoin('mensajes', function ($join) {
                $join->on(DB::raw('CONCAT(contactos.codigo_telefono, contactos.telefono)'), '=', 'mensajes.wa_from')
                    ->orOn(DB::raw('CONCAT(contactos.codigo_telefono, contactos.telefono)'), '=', 'mensajes.wa_to');
            })
            ->where('contactos.estado', Contacto::ACTIVO)
            ->groupBy('contactos.id', 'contactos.nombre', 'contactos.apellido', 'contactos.codigo_telefono', 'contactos.telefono')
            ->orderByDesc('fecha')
            ->get();

        return ChatResource::collection($usuarios);
    }

    public function chat(Request $request, $contacto)
    {
        $usuario = Usuario::where('uuid', $request->user()->uuid)->first();
        $offset = $request->input('offset', 0); // Valor predeterminado 0
        $limit = $request->input('limit', 50);  // Valor predeterminado 50

        // Buscar contacto por teléfono completo
        $contacto = Contacto::whereRaw("CONCAT(contactos.codigo_telefono, contactos.telefono) LIKE ?", ["%{$contacto}%"])
            ->first();

        if (!$contacto) {
            return [
                'estado' => 'error',
                'mensaje' => 'Contacto no encontrado'
            ];
        }

        $config = ConfiguracionMeta::where('cod_empresa', $usuario?->empresa?->id)->firstOrFail();
        $phoneNumberId = $config->phone_number_id;

        // Traer mensajes entre el contacto y el número de la empresa
        $mensajes = Mensaje::where(function ($query) use($phoneNumberId) {
                $query->where('wa_from', $phoneNumberId)
                    ->orWhere('wa_to', $phoneNumberId);
            })
            ->where(function ($query) use ($contacto) {
                $numeroCompleto = $contacto->codigo_telefono . $contacto->telefono;
                $query->where('wa_from', $numeroCompleto)
                    ->orWhere('wa_to', $numeroCompleto);
            })
            ->orderByDesc('created_at')
            ->skip($offset)
            ->take($limit)
            ->get();

        // Estado del contacto (últimos 24h)
        $estadoContacto = Mensaje::where(function ($query) use($phoneNumberId) {
                $query->where('wa_from', $phoneNumberId)
                    ->whereIn('estado', [
                        Mensaje::ENVIADO,
                        Mensaje::ENTREGADO,
                        Mensaje::LEIDO
                    ])
                    ->orWhere('wa_to', $phoneNumberId);
            })
            ->where(function ($query) use ($contacto) {
                $numeroCompleto = $contacto->codigo_telefono . $contacto->telefono;
                $query->where('wa_from', $numeroCompleto)
                    ->whereIn('estado', [
                        Mensaje::ENVIADO,
                        Mensaje::ENTREGADO,
                        Mensaje::LEIDO
                    ])
                    ->orWhere('wa_to', $numeroCompleto);
            })
            ->where('created_at', '>=', now()->subHours(24))
            ->count() ?? 0;

        // Agrupar los mensajes por día
        $mensajesAgrupados = $mensajes->groupBy(function ($mensaje) {
            $fecha = Carbon::parse($mensaje->created_at);

            if ($fecha->isToday()) {
                return 'Hoy';
            } elseif ($fecha->isYesterday()) {
                return 'Ayer';
            } else {
                return $fecha->format('d/m/Y');
            }
        });

        // Ordenar cronológicamente dentro de cada grupo
        foreach ($mensajesAgrupados as $key => $grupo) {
            $mensajesAgrupados[$key] = $grupo->sortBy('created_at')->values();
        }

        // Ordenar los grupos cronológicamente
        $mensajesAgrupados = $mensajesAgrupados->sortKeysUsing(function ($key) {
            if ($key === 'Hoy') {
                return Carbon::today()->timestamp;
            } elseif ($key === 'Ayer') {
                return Carbon::yesterday()->timestamp;
            } else {
                return Carbon::createFromFormat('d/m/Y', $key)->timestamp;
            }
        });

        // Convertir fechas a horas para el frontend
        // Asegurarte de que los índices se reordenen
        $mensajesAgrupados = collect($mensajesAgrupados)
            ->sortBy(function ($mensajes, $fecha) {
                return \Carbon\Carbon::createFromFormat('d/m/Y', $fecha);
            })
            ->toArray();
        foreach ($mensajesAgrupados as $index => $grupos) {
            foreach ($grupos as $key => $mensaje) {
                $dateTime = new DateTime($mensaje['created_at']);
                $mensajesAgrupados[$index][$key]['created_at'] = $dateTime->format("h:i A");
            }
        }

        $info['contacto'] = $contacto;
        $info['estadoContacto'] = $estadoContacto;
        $info['mensajesAgrupados'] = $mensajesAgrupados;

        return [
            'estado' => 'success',
            'html'   => view('chats.chat', $info)->render(),
            'offset' => $offset,
            'limit'  => $limit
        ];
    }

    public function store(Request $request)
    {
        $offset = $request->input('offset', 0); // Valor predeterminado 0
        $limit = $request->input('limit', 50); // Valor predeterminado 50

        $filename = null;

        $contacto = Contacto::whereRaw("CONCAT(codigo_telefono, telefono) = {$request->input('id')}")->first();
        if (!$contacto) {
            return [
                'estado' => 'error',
                'mensaje' => 'Contacto no encontrado'
            ];
        }

        // Datos base del mensaje
        $datos = [
            'campaign_id' => null,
            'contact_id'  => $contacto->id,
            'wa_message_id' => null,
            'wa_from' => $this->phone_number_id,
            'wa_to'   => $contacto->numero_completo,
            'type'    => Mensaje::TEXTO,
            'body'    => $request->input('mensaje') ?? null,
            'metadata'=> null,
            'estado'  => Mensaje::ENVIADO,
            'sent_at' => now(),
        ];

        // --- Envío de imagen (base64 desde canvas/chat) ---
        if ($request->get('imagen')) {
            $data = $request->get('imagen');
            $data = str_replace('data:image/png;base64,', '', $data);
            $data = str_replace(' ', '+', $data);
            $data = base64_decode($data);

            $idArchivo = time();
            $nombreOriginal = $idArchivo . '.jpg';
            $rutaImagen = public_path('img/chat/' . $nombreOriginal);
            file_put_contents($rutaImagen, $data);

            $datos['metadata'] = (object) [
                "from" => $this->phone_number_id,
                "id" => null,
                "timestamp" => $idArchivo,
                "type" => "image",
                "image" => (object) [
                    "caption" => $datos['body'] ?? null,
                    "mime_type" => "image\/jpg",
                    "sha256" => null,
                    "id" => $idArchivo
                ],
            ];
            $datos['type'] = Mensaje::IMAGEN;
        }

        if ($request->hasFile('archivo') && $request->file('archivo')) {
            $archivo = $request->file('archivo')[0];
            $idArchivo = time();
            $extension = $archivo->getClientOriginalExtension();
            $nombreOriginal =  $idArchivo;
            $mimeType = $archivo->getMimeType();

            // Verificar si el archivo es un PDF
            $allowedMimeTypes = [
                'application/pdf',
                'application/msword', // .doc
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
                'application/vnd.ms-excel', // .xls
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // .xlsx
                'text/plain',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            ];

            if (in_array($mimeType, $allowedMimeTypes)) {
                $maxSize = 100 * 1024 * 1024; // 100 MB en bytes

                if ($archivo->getSize() > $maxSize) {
                    throw new ErrorException('El archivo excede el tamaño máximo permitido de 100MB.');
                }
                $nombreOriginal = $nombreOriginal.'.'.$extension;
                $path = $archivo->storeAs('campanas/documentos', $nombreOriginal, 'public');
                $datos['metadata'] = (object) [
                    "from" => $this->phone_number_id,
                    "id" => null,
                    "timestamp" => $idArchivo,
                    "type" => "document",
                    "document" => (object) [
                        "caption" => $datos['body'] ?? null,
                        "filename" => $nombreOriginal,
                        "mime_type" => $mimeType,
                        "sha256" => null,
                        "id" => $idArchivo,
                    ],
                ];
                $datos['type'] = Mensaje::DOCUMENTO;
            } elseif (str_starts_with($mimeType, 'image/')) {
                $maxSize = 5 * 1024 * 1024; // 5 MB en bytes

                if ($archivo->getSize() > $maxSize) {
                    throw new ErrorException('El archivo excede el tamaño máximo permitido de 5MB.');
                }
                $nombreOriginal = $nombreOriginal. '.jpg';
                $path = $archivo->storeAs('campanas/img', $nombreOriginal, 'public');
                $datos['metadata'] = (object) [
                    "from" => $this->phone_number_id,
                    "id" => null,
                    "timestamp" => $idArchivo,
                    "type" => "image",
                    "image" => (object) [
                        "caption" => $datos['body'] ?? null,
                        "mime_type" => "image\/jpg",
                        "sha256" => null,
                        "id" => $idArchivo,
                    ],
                ];
                $datos['type'] = Mensaje::IMAGEN;
            } elseif ($mimeType == 'video/mp4') {
                $maxSize = 16 * 1024 * 1024; // 16 MB en bytes

                if ($archivo->getSize() > $maxSize) {
                    throw new ErrorException('El archivo excede el tamaño máximo permitido de 16MB.');
                }
                $path = $archivo->storeAs('campanas/videos', $nombreOriginal, 'public');
                $datos['metadata'] = (object) [
                    "from" => $this->phone_number_id,
                    "id" => null,
                    "timestamp" => $idArchivo,
                    "type" => "video",
                    "video" => (object) [
                        "caption" => $datos['body'] ?? null,
                        "mime_type" => "video\/mp4",
                        "sha256" => null,
                        "id" => $idArchivo,
                    ],
                ];
                $datos['type'] = Mensaje::VIDEO;
            } else {
                throw new ErrorException('Error al intentar cargar la imagen.');
            }

            // if (!file_exists($datos['metadata'])) {
            //     throw new ErrorException('Error al intentar guardar el archivo.');
            // }
        } else {
            if ($request->file('archivo')) {
                throw new ErrorException('Por favor, revise si el archivo cuenta con las condiciones establecidas para el envío.');
            }
        }

        // --- Envío de audio (grabación convertida a ogg) ---
        if ($request->hasFile('audio') && $request->file('audio')->isValid()) {
            $audioFile = $request->file('audio');

            $filename = 'grabacion_' . time();
            $webmPath = storage_path("app/temp/{$filename}.webm");

            if (!file_exists(dirname($webmPath))) {
                mkdir(dirname($webmPath), 0755, true);
            }

            $audioFile->move(dirname($webmPath), basename($webmPath));
            $tempOggPath = storage_path("app/temp/{$filename}.ogg");

            $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

            $inputPath = $isWindows
                ? "\"{$webmPath}\""
                : escapeshellarg($webmPath);

            $outputPath = $isWindows
                ? "\"{$tempOggPath}\""
                : escapeshellarg($tempOggPath);

            $command = "ffmpeg -i {$inputPath} -c:a libopus {$outputPath}";
            exec($command . ' 2>&1', $output, $returnCode);

            @unlink($webmPath);

            if ($returnCode !== 0) {
                throw new ErrorException('Error al convertir el archivo de audio.');
            }

            // Guardar en storage/app/public/audios/chat
            $storagePath = 'audios/chat/' . $filename . '.ogg';

            Storage::disk('public')->put(
                $storagePath,
                file_get_contents($tempOggPath)
            );

            @unlink($tempOggPath);

            $datos['type'] = Mensaje::AUDIO;
            $datos['body'] = url(Storage::url($storagePath));
        }

        // --- Envío a WhatsApp Cloud API ---
        if ($datos['type'] == Mensaje::AUDIO) {
            $link_id = new LinkID($datos['body']);
            $response = $this->whatsapp_cloud_api->sendAudio($datos['wa_to'], $link_id);
        } elseif ($datos['type'] == Mensaje::IMAGEN) {
            $link_id = new LinkID(asset('img/chat/'.$nombreOriginal));
            $response = $this->whatsapp_cloud_api->sendImage($datos['wa_to'], $link_id, $datos['body']);
        } elseif ($datos['type'] == Mensaje::VIDEO) {
            $link_id = new LinkID(asset('videos/chat/'.$nombreOriginal));
            $response = $this->whatsapp_cloud_api->sendVideo($datos['wa_to'], $link_id, $datos['body']);
        } elseif ($datos['type'] == Mensaje::DOCUMENTO) {
            $document_link = asset('documentos/chat/'.$nombreOriginal);
            $link_id = new LinkID($document_link);
            $response = $this->whatsapp_cloud_api->sendDocument($datos['wa_to'], $link_id, $nombreOriginal, $datos['body']);
        } else { // TEXTO
            $response = $this->whatsapp_cloud_api->sendTextMessage($datos['wa_to'], $datos['body']);
        }

        if ($response?->body()) {
            $data = json_decode($response?->body());
            $messages = $data->messages ?? [];
            $datos['wa_message_id'] = $messages[0]->id ?? null;
            if (in_array($datos['type'], [Mensaje::IMAGEN, Mensaje::DOCUMENTO, Mensaje::VIDEO])) {
                $datos['metadata']->id = $datos['wa_message_id'];
            }
        }

        $mensaje = Mensaje::create($datos);
        if (!$mensaje) {
            throw new ErrorException("Error al intentar enviar el mensaje.");
        }

        // === Recargar mensajes del chat ===
        $mensajes = Mensaje::where(function ($query) {
                $query->where('wa_from', $this->phone_number_id)
                    ->orWhere('wa_to', $this->phone_number_id);
            })
            ->where(function ($query) use ($contacto) {
                $query->where('wa_from', $contacto->numero_completo)
                    ->orWhere('wa_to', $contacto->numero_completo);
            })
            ->orderByDesc('created_at')
            ->skip($offset)
            ->take($limit)
            ->get();

        // Agrupar mensajes por día
        $mensajesAgrupados = $mensajes->groupBy(function ($mensaje) {
            $fecha = Carbon::parse($mensaje->created_at);
            if ($fecha->isToday()) return 'Hoy';
            elseif ($fecha->isYesterday()) return 'Ayer';
            else return $fecha->format('d/m/Y');
        });

        foreach ($mensajesAgrupados as $key => $grupo) {
            $mensajesAgrupados[$key] = $grupo->sortBy('created_at')->values();
        }

        $mensajesAgrupados = $mensajesAgrupados->sortKeysUsing(function ($key) {
            if ($key === 'Hoy') return Carbon::today()->timestamp;
            elseif ($key === 'Ayer') return Carbon::yesterday()->timestamp;
            else return Carbon::createFromFormat('d/m/Y', $key)->timestamp;
        });

        // Asegurarte de que los índices se reordenen
        $mensajesAgrupados = collect($mensajesAgrupados)
            ->sortBy(function ($mensajes, $fecha) {
                return \Carbon\Carbon::createFromFormat('d/m/Y', $fecha);
            })
            ->toArray();
        foreach ($mensajesAgrupados as $index => $grupos) {
            foreach ($grupos as $key => $mensaje) {
                $dateTime = new DateTime($mensaje['created_at']);
                $mensajesAgrupados[$index][$key]['created_at'] = $dateTime->format("h:i A");
            }
        }

        $info['contacto'] = $contacto;
        $info['mensajesAgrupados'] = $mensajesAgrupados;

        return [
            'estado' => 'success',
            'html' => view('chats.mensaje', $info)->render(),
            'offset' => $offset,
            'limit' => $limit
        ];
    }

    public function macarMensaleLeido($de, $para)
    {
        $mensajes = Mensaje::where('wa_to', $para)
            ->where('wa_from', $de)
            ->whereIn('estado', [Mensaje::ENTREGADO, Mensaje::ENVIADO])
            ->get();

        foreach ($mensajes as $key => $mensaje) {
            $mensaje->update(['estado' => Mensaje::LEIDO]);
            $this->whatsapp_cloud_api->markMessageAsRead($mensaje->wa_message_id);
        }

        // broadcast(new MensajeLeido($de))->toOthers();

        return ['estado' => 'success'];
    }

    public function actualizarContactos(Request $request)
    {
        $usuarios = Contacto::selectRaw('contactos.id as id, contactos.nombre, contactos.apellido,
            contactos.codigo_telefono, contactos.telefono, MAX(mensajes.created_at) as fecha,
            COUNT(CASE
                WHEN mensajes.estado IN (' . Mensaje::ENTREGADO . ','.Mensaje::ENVIADO.')
                AND mensajes.wa_to = "' . $this->phone_number_id . '"
                THEN 1 ELSE NULL
            END) as mensajes_no_leidos,
            (SELECT body FROM mensajes WHERE (CONCAT(contactos.codigo_telefono, contactos.telefono) = mensajes.wa_from AND mensajes.wa_to = "' . $this->phone_number_id . '")
               OR (CONCAT(contactos.codigo_telefono, contactos.telefono) = mensajes.wa_to AND mensajes.wa_from = "' . $this->phone_number_id . '" ) ORDER BY mensajes.created_at DESC
            LIMIT 1) as mensaje,
            (SELECT type FROM mensajes WHERE (CONCAT(contactos.codigo_telefono, contactos.telefono) = mensajes.wa_from AND mensajes.wa_to = "' . $this->phone_number_id . '")
               OR (CONCAT(contactos.codigo_telefono, contactos.telefono) = mensajes.wa_to AND mensajes.wa_from = "' . $this->phone_number_id . '" ) ORDER BY mensajes.created_at DESC
            LIMIT 1) as tipo_mensaje')
            ->leftJoin('mensajes', function ($join) {
                $join->on(DB::raw('CONCAT(contactos.codigo_telefono, contactos.telefono)'), '=', 'mensajes.wa_from')
                    ->orOn(DB::raw('CONCAT(contactos.codigo_telefono, contactos.telefono)'), '=', 'mensajes.wa_to');
            })
            ->where('contactos.estado', Contacto::ACTIVO)
            ->where(function($query) use($request) {
                if ($request->input('valor')) {
                    $query->whereRaw("CONCAT(contactos.nombre, ' ', contactos.apellido) LIKE ?", ["%{$request->input('valor')}%"])
                        ->orWhereRaw("contactos.telefono LIKE ?", ["%{$request->input('valor')}%"]);
                }
            })
            ->groupBy('contactos.id', 'nombre', 'apellido', 'contactos.codigo_telefono', 'contactos.telefono')
            ->orderByDesc('fecha')
            ->get();

        // dd($usuarios, $this->phone_number_id);
        $info['usuarios'] = $usuarios;

        $respuesta["estado"] = "success";
        $respuesta['html'] = view("chats.listado-contactos", $info)->render();

        return response()->json($respuesta);
    }

    public function actualizarMensaje(Request $request, $contacto)
    {
        $offset = $request->input('offset', 0); // Valor predeterminado 0
        $limit = $request->input('limit', 50);  // Valor predeterminado 50

        // Buscar contacto
        $contacto = Contacto::whereRaw("CONCAT(contactos.codigo_telefono, contactos.telefono) LIKE ?", ["%{$contacto}%"])
            ->first();

        if (!$contacto) {
            return [
                'estado' => 'error',
                'mensaje' => 'Contacto no encontrado'
            ];
        }

        $numeroCompleto = $contacto->codigo_telefono . $contacto->telefono;

        // Cantidad total de mensajes con ese contacto
        $cantidad_mensajes = Mensaje::where(function ($query) use ($numeroCompleto) {
                $query->where(function ($q) use ($numeroCompleto) {
                    $q->where('wa_from', $this->phone_number_id)
                    ->where('wa_to', $numeroCompleto);
                })->orWhere(function ($q) use ($numeroCompleto) {
                    $q->where('wa_from', $numeroCompleto)
                    ->where('wa_to', $this->phone_number_id);
                });
            })->count() ?? 0;

        // Consulta de mensajes (paginada)
        $query = Mensaje::where(function ($query) use ($numeroCompleto) {
                $query->where(function ($q) use ($numeroCompleto) {
                    $q->where('wa_from', $this->phone_number_id)
                    ->where('wa_to', $numeroCompleto);
                })->orWhere(function ($q) use ($numeroCompleto) {
                    $q->where('wa_from', $numeroCompleto)
                    ->where('wa_to', $this->phone_number_id);
                });
            })
            ->orderBy('created_at', $offset > 0 ? 'asc' : 'desc')
            ->skip($offset)
            ->take($limit);

        // Invertir orden para scroll up
        if ($offset > 0) {
            $mensajes = $query->get()->reverse();
        } else {
            $mensajes = $query->get();
        }

        // Agrupar los mensajes por día
        $mensajesAgrupados = $mensajes->groupBy(function ($mensaje) {
            $fecha = Carbon::parse($mensaje->created_at);

            if ($fecha->isToday()) {
                return 'Hoy';
            } elseif ($fecha->isYesterday()) {
                return 'Ayer';
            } else {
                return $fecha->format('d/m/Y');
            }
        });

        // Ordenar cronológicamente dentro de cada grupo
        foreach ($mensajesAgrupados as $key => $grupo) {
            $mensajesAgrupados[$key] = $grupo->sortBy('created_at')->values();
        }

        // Ordenar los grupos cronológicamente
        $mensajesAgrupados = $mensajesAgrupados->sortKeysUsing(function ($key) {
            if ($key === 'Hoy') {
                return Carbon::today()->timestamp;
            } elseif ($key === 'Ayer') {
                return Carbon::yesterday()->timestamp;
            } else {
                return Carbon::createFromFormat('d/m/Y', $key)->timestamp;
            }
        });

        // Convertir a array y ajustar formato de hora
        // Asegurarte de que los índices se reordenen
        $mensajesAgrupados = collect($mensajesAgrupados)
            ->sortBy(function ($mensajes, $fecha) {
                return \Carbon\Carbon::createFromFormat('d/m/Y', $fecha);
            })
            ->toArray();
        foreach ($mensajesAgrupados as $index => $grupos) {
            foreach ($grupos as $key => $mensaje) {
                $dateTime = new DateTime($mensaje['created_at']);
                $mensajesAgrupados[$index][$key]['created_at'] = $dateTime->format("h:i A");
            }
        }

        $info['contacto'] = $contacto;
        $info['mensajesAgrupados'] = $mensajesAgrupados;

        return [
            'estado' => 'success',
            'html'   => view('chats.mensaje', $info)->render(),
            'offset' => $offset,
            'limit'  => $limit > $cantidad_mensajes ? 50 : $limit
        ];
    }

    public function infoContacto($contacto)
    {
        $contacto = Contacto::selectRaw('contactos.id as id, CONCAT(contactos.nombre, " ",contactos.apellido) as nombre_contacto,
            DATE(contactos.created_at) as fecha_creacion, estado_chatbot, estado_chatbot_ia, CONCAT(contactos.codigo_telefono, contactos.telefono) as numero')
            ->whereRaw("CONCAT(codigo_telefono, '', telefono) LIKE ?", ["%{$contacto}%"])
            ->where('cod_empresa', $this->uuid)
            ->first();

        return [
            'estado' => 'success',
            'contacto' => $contacto,
        ];
    }
}
