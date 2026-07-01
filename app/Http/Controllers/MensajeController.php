<?php

namespace App\Http\Controllers;

use App\Exceptions\ErrorException;
use App\Models\ConfiguracionMeta;
use App\Models\Contacto;
use App\Models\Conversacion;
use App\Models\Mensaje;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;

class MensajeController extends Controller
{
    public function index()
    {
        $contactos = Contacto::query()
            ->leftJoin('conversaciones', function ($join) {
                $join->on('contactos.id', '=', 'conversaciones.contacto_id')
                    ->where(
                        'conversaciones.phone_number_id',
                        $this->phone_number_id
                    );
            })
            ->with([
                'conversacion' => function ($q) {
                    $q->where(
                        'phone_number_id',
                        $this->phone_number_id
                    );
                }
            ])
            ->where('contactos.estado', Contacto::ACTIVO)
            ->orderByDesc('conversaciones.ultima_fecha')
            ->select('contactos.*')
            ->get();

        $info['numero'] = $this->phone_number_id;
        $info['contactos'] = $contactos;
        $info['datosPerfilWhatsapp'] = $this->whatsapp_cloud_api->businessProfile('about,address,description,email,profile_picture_url,websites,vertical')->body() ?? null;
        if ($info['datosPerfilWhatsapp']) {
            $info['datosPerfilWhatsapp'] = json_decode($info['datosPerfilWhatsapp'], true)['data'][0];
        }
        $info['datosNumero'] = getPhoneNumbers($this->waba_id, $this->version, $this->token);

        return view('chats.index', $info);
    }

    public function chat(Request $request, $contacto)
    {
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

        // Traer mensajes entre el contacto y el número de la empresa
        $mensajes = Mensaje::where(function ($query) {
            $query->where('wa_from', $this->phone_number_id)
                ->orWhere('wa_to', $this->phone_number_id);
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
        $estadoContacto = Mensaje::where(function ($query) {
            $query->where('wa_from', $this->phone_number_id)
                ->whereIn('estado', [
                    Mensaje::ENVIADO,
                    Mensaje::ENTREGADO,
                    Mensaje::LEIDO
                ])
                ->orWhere('wa_to', $this->phone_number_id);
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
        $mensajesAgrupados = collect($mensajesAgrupados)
            ->sortKeysUsing(function ($key) {
                // Hoy y Ayer
                if ($key === 'Hoy') {
                    return Carbon::today()->timestamp;
                } elseif ($key === 'Ayer') {
                    return Carbon::yesterday()->timestamp;
                }

                // Intentar parsear fechas d/m/Y
                try {
                    return Carbon::createFromFormat('d/m/Y', $key)->timestamp;
                } catch (\Exception $e) {
                    // Si no se puede parsear, poner al final
                    return PHP_INT_MAX;
                }
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
        $path = null;
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
            'metadata' => null,
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
                $nombreOriginal = $nombreOriginal . '.' . $extension;
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
                $nombreOriginal = $nombreOriginal . '.jpg';
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
            $link_id = new LinkID(url(Storage::url($path)));
            $response = $this->whatsapp_cloud_api->sendImage($datos['wa_to'], $link_id, $datos['body']);
        } elseif ($datos['type'] == Mensaje::VIDEO) {
            $link_id = new LinkID(url(Storage::url($path)));
            $response = $this->whatsapp_cloud_api->sendVideo($datos['wa_to'], $link_id, $datos['body']);
        } elseif ($datos['type'] == Mensaje::DOCUMENTO) {
            $document_link = url(Storage::url($path));
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

        $mensajesAgrupados = collect($mensajesAgrupados)
            ->sortKeysUsing(function ($key) {
                if ($key === 'Hoy') {
                    return Carbon::today()->timestamp;
                } elseif ($key === 'Ayer') {
                    return Carbon::yesterday()->timestamp;
                }

                // Solo intentar parsear si parece una fecha d/m/Y
                if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $key)) {
                    return Carbon::createFromFormat('d/m/Y', $key)->timestamp;
                }

                // Si no coincide, poner un valor alto para que quede al final
                return PHP_INT_MAX;
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

        $config = ConfiguracionMeta::where('estado', ConfiguracionMeta::ACTIVO)->where('phone_number_id', $this->phone_number_id)->first();
        $contacto = Contacto::whereRaw("CONCAT(codigo_telefono, '', telefono) LIKE ?", ["%{$de}%"])
                ->where('cod_empresa', $config->cod_empresa)
                ->first();
        Conversacion::where('contacto_id', $contacto->id)
            ->where('phone_number_id', $this->phone_number_id)
            ->update([
                'mensajes_no_leidos' => 0
            ]);

        // broadcast(new MensajeLeido($de))->toOthers();

        return ['estado' => 'success'];
    }

    public function actualizarContactos(Request $request)
    {
        $contactos = Contacto::query()
            ->leftJoin('conversaciones', function ($join) {
                $join->on('contactos.id', '=', 'conversaciones.contacto_id')
                    ->where(
                        'conversaciones.phone_number_id',
                        $this->phone_number_id
                    );
            })
            ->with([
                'conversacion' => function ($q) {
                    $q->where(
                        'phone_number_id',
                        $this->phone_number_id
                    );
                }
            ])
            ->where('contactos.estado', Contacto::ACTIVO)
            ->orderByDesc('conversaciones.ultima_fecha')
            ->select('contactos.*')
            ->get();

        $info['contactos'] = $contactos;

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
        $mensajesAgrupados = collect($mensajesAgrupados)
            ->sortKeysUsing(function ($key) {
                if ($key === 'Hoy') {
                    return Carbon::today()->timestamp;
                } elseif ($key === 'Ayer') {
                    return Carbon::yesterday()->timestamp;
                }

                // Solo intentar parsear si parece una fecha d/m/Y
                if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $key)) {
                    return Carbon::createFromFormat('d/m/Y', $key)->timestamp;
                }

                // Si no coincide, poner un valor alto para que quede al final
                return PHP_INT_MAX;
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
