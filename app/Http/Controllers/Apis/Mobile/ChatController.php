<?php

namespace App\Http\Controllers\Apis\Mobile;

use App\Events\MensajeSent;
use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Models\Conversacion;
use App\Models\Mensaje;
use App\Models\WhatsappAccount;
use App\Models\WhatsappNumber;
use App\Services\Chats\ConversacionAgente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;

class ChatController extends Controller
{
    // Obtener el phone_number_id dinámicamente basado en la empresa del usuario
    private function getPhoneNumberId($user)
    {
        // Número asignado al usuario (o el principal si no tiene)
        $numero = WhatsappNumber::paraUsuario($user);
        if ($numero) {
            return $numero->phone_number_id;
        }

        $config = WhatsappAccount::where('cod_empresa', $user->cod_empresa)
            ->where('estado', WhatsappAccount::CONECTADO)
            ->first();

        if (!$config) {
            throw new \Exception(__('No hay configuración de WhatsApp para esta empresa.'));
        }

        return $config->phone_number_id;
    }

    // Instanciar la API de WhatsApp dinámicamente (asumiendo que tienes un helper o método en WhatsappAccount)
    private function getWhatsappApi($phoneNumberId)
    {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));
        // Los números secundarios no están en whatsapp_accounts: el token es el de su cuenta
        $config = WhatsappAccount::where('phone_number_id', $phoneNumberId)->first()
            ?? WhatsappNumber::where('phone_number_id', $phoneNumberId)->first()?->cuenta;
        if (!$config || !$config->access_token) {
            throw new \Exception(__('Token de WhatsApp no configurado.'));
        }

        $token = Crypt::decrypt($config->access_token);
        // Ajusta esto según cómo instancies tu API en tu proyecto
        return new \Netflie\WhatsAppCloudApi\WhatsAppCloudApi([
            'from_phone_number_id' => $phoneNumberId,
            'access_token' => $token,
            'graph_version' => $version, // O la versión que uses
        ]);
    }

    /**
     * LISTA DE CHATS (Contactos con última conversación)
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $phoneNumberId = $this->getPhoneNumberId($user);

        $p = $phoneNumberId;
        $ultimo = fn (string $col) => "(SELECT m.{$col} FROM mensajes m
            WHERE (m.wa_from = contactos.numero_completo AND m.wa_to = ?) OR (m.wa_from = ? AND m.wa_to = contactos.numero_completo)
            ORDER BY m.created_at DESC LIMIT 1)";

        // El último mensaje, su fecha y los no leídos salen de la tabla mensajes (igual que el chat web),
        // así la lista siempre está al día aunque la tabla conversaciones no se haya actualizado.
        $contactos = Contacto::query()
            ->where('contactos.estado', Contacto::ACTIVO)
            ->where('contactos.cod_empresa', $user->cod_empresa)
            ->select('contactos.*')
            ->selectRaw(
                $ultimo('body') . ' as ult_body, ' . $ultimo('type') . ' as ult_tipo, ' . $ultimo('created_at') . ' as ult_fecha, '
                . '(SELECT COUNT(*) FROM mensajes u WHERE u.wa_from = contactos.numero_completo AND u.wa_to = ? AND u.estado IN (?, ?)) as sin_leer',
                [$p, $p, $p, $p, $p, $p, $p, Mensaje::ENTREGADO, Mensaje::ENVIADO]
            )
            ->orderByRaw('ult_fecha IS NULL, ult_fecha DESC')
            ->get();

        $chats = $contactos->map(function ($contacto) {
            return [
                'id' => $contacto->numero_completo,
                'contact_id' => $contacto->id,
                'nombre' => trim(($contacto->nombre ?? '') . ' ' . ($contacto->apellido ?? '')),
                'telefono' => $contacto->telefono,
                'codigo_telefono' => $contacto->codigo_telefono,
                'numero_completo' => $contacto->numero_completo,
                'ultimo_mensaje' => $this->etiquetaUltimo($contacto->ult_tipo, $contacto->ult_body),
                'hora_ultimo_mensaje' => $contacto->ult_fecha,
                'no_leidos' => (int) $contacto->sin_leer,
                'estado_chatbot' => $contacto->estado_chatbot,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $chats
        ]);
    }
    /**
     * MENSAJES DE UN CHAT ESPECÍFICO
     */
    public function showMessages(Request $request, $numeroCompleto): JsonResponse
    {
        $user = $request->user();
        $phoneNumberId = $this->getPhoneNumberId($user);
        $limit = $request->input('limit', 50);
        $before_id = $request->input('before_id'); // Para paginar hacia arriba

        $contacto = Contacto::where('numero_completo', $numeroCompleto)->first();

        if (!$contacto) {
            return response()->json(['success' => false, 'message' => __('Contacto no encontrado')], 404);
        }

        $query = Mensaje::where(function ($q) use ($phoneNumberId, $numeroCompleto) {
                $q->where(function ($q2) use ($phoneNumberId, $numeroCompleto) {
                    $q2->where('wa_from', $phoneNumberId)->where('wa_to', $numeroCompleto);
                })->orWhere(function ($q2) use ($phoneNumberId, $numeroCompleto) {
                    $q2->where('wa_from', $numeroCompleto)->where('wa_to', $phoneNumberId);
                });
            })
            ->orderByDesc('created_at');

        // Paginación hacia arriba (cargar más mensajes antiguos)
        if ($before_id && ($ref = Mensaje::find($before_id))) {
            $query->where('created_at', '<', $ref->created_at);
        }

        $mensajes = $query->take($limit)->get()->reverse(); // Invertir para que queden en orden cronológico

        return response()->json([
            'success' => true,
            'data' => $mensajes->map(function ($m) use ($phoneNumberId) {
                return [
                    'id' => $m->id,
                    'es mio' => $m->wa_from === $phoneNumberId, // Para saber de qué lado pintar la burbuja
                    'tipo' => $m->type,
                    'contenido' => $m->body,
                    'metadata' => $m->metadata,
                    'estado' => $m->estado, // 'enviado', 'entregado', 'leido'
                    'fecha' => $m->created_at->toISOString(), // Flutter se encarga de formatear "Hoy", "Ayer"
                    'url_archivo' => $this->getFileUrl($m),
                ];
            })->values()->toArray(),
            'contacto' => [
                'nombre' => $contacto->nombre . ' ' . $contacto->apellido,
                'telefono' => $contacto->numero_completo,
            ]
        ]);
    }

    /**
     * ENVIAR MENSAJE (Solo texto por ahora para empezar)
     */
    public function sendMessage(Request $request, $numeroCompleto): JsonResponse
    {
        $user = $request->user();
        $phoneNumberId = $this->getPhoneNumberId($user);
        $whatsappApi = $this->getWhatsappApi($phoneNumberId);

        $contacto = Contacto::where('numero_completo', $numeroCompleto)->first();
        if (!$contacto) {
            return response()->json(['success' => false, 'message' => __('Contacto no encontrado')], 404);
        }

        // Otro agente puede estar atendiendo este chat
        if ($bloqueo = ConversacionAgente::reclamar($contacto->id, $phoneNumberId, $user)) {
            return response()->json(['success' => false, 'message' => $bloqueo], 409);
        }

        $mensajeTexto = $request->input('mensaje');

        // 1. Enviar a WhatsApp Cloud API
        try {
            $response = $whatsappApi->sendTextMessage($numeroCompleto, $mensajeTexto);
            $waMessageId = null;

            if ($response?->body()) {
                $data = json_decode($response->body());
                $waMessageId = $data->messages[0]->id ?? null;
            }
        } catch (\Exception $e) {
            return $this->errorEnvio($e->getMessage());
        }

        // 2. Guardar en BD
        $mensaje = Mensaje::create([
            'contact_id' => $contacto->id,
            'wa_message_id' => $waMessageId,
            'wa_from' => $phoneNumberId,
            'wa_to' => $numeroCompleto,
            'type' => Mensaje::TEXTO,
            'body' => $mensajeTexto,
            'estado' => Mensaje::ENVIADO,
            'sent_at' => now(),
        ]);

        // 3. Actualizar conversación
        Conversacion::updateOrCreate(
            [
                'contacto_id' => $contacto->id,
                'phone_number_id' => $phoneNumberId,
            ],
            $this->datosConversacion(substr($mensajeTexto, 0, 100), Mensaje::TEXTO)
        );

        // 3.1 Avisar al chat web y a los demás dispositivos
        $this->difundirSalida($mensaje, $phoneNumberId);

        // 4. Retornar el mensaje creado para que Flutter lo agregue a la lista al instante
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $mensaje->id,
                'es mio' => true,
                'tipo' => $mensaje->type,
                'contenido' => $mensaje->body,
                'estado' => $mensaje->estado,
                'fecha' => $mensaje->created_at->toISOString(),
                'url_archivo' => null,
            ]
        ]);
    }

    /**
     * ENVIAR ARCHIVO (imagen, video, audio o documento) con texto opcional.
     * multipart: archivo (obligatorio), caption (opcional). El tipo se detecta en el servidor por el contenido real.
     */
    public function sendMedia(Request $request, $numeroCompleto): JsonResponse
    {
        $user = $request->user();

        // hasFile() es false cuando PHP rechazó la subida por tamaño, por eso se mira el archivo directamente
        if (($subido = $request->file('archivo')) && !$subido->isValid()) {
            return response()->json([
                'success' => false,
                'message' => __('El servidor no pudo recibir el archivo (límite de subida: :l). Revisa upload_max_filesize y post_max_size.', ['l' => ini_get('upload_max_filesize')]),
            ], 413);
        }

        $v = Validator::make($request->all(), [
            'archivo' => 'required|file|max:102400',
            'caption' => 'nullable|string|max:1024',
        ]);
        if ($v->fails()) {
            return response()->json(['success' => false, 'message' => $v->errors()->first()], 422);
        }

        try {
            $phoneNumberId = $this->getPhoneNumberId($user);
            $cuenta = $this->cuentaDelNumero($phoneNumberId);
            $token = Crypt::decrypt($cuenta->access_token);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        $contacto = Contacto::where('numero_completo', $numeroCompleto)->first();
        if (!$contacto) {
            return response()->json(['success' => false, 'message' => __('Contacto no encontrado')], 404);
        }

        if ($bloqueo = ConversacionAgente::reclamar($contacto->id, $phoneNumberId, $user)) {
            return response()->json(['success' => false, 'message' => $bloqueo], 409);
        }

        $archivo = $request->file('archivo');
        $mime = $this->mimeNormalizado($archivo);
        $caption = trim((string) $request->input('caption', '')) ?: null;
        $ext = strtolower($archivo->getClientOriginalExtension() ?: $archivo->guessExtension() ?: 'bin');

        $clase = $this->claseDeArchivo($mime);
        if (!$clase) {
            return response()->json(['success' => false, 'message' => __('Tipo de archivo no permitido por WhatsApp.')], 422);
        }
        [$tipoMsg, $claveMeta, $maxMb] = $clase;
        if ($archivo->getSize() > $maxMb * 1024 * 1024) {
            return response()->json(['success' => false, 'message' => __('El archivo supera el máximo de :mb MB para este tipo.', ['mb' => $maxMb])], 422);
        }

        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        // 1. Subir el archivo a Meta
        try {
            $subida = Http::withToken($token)->timeout(120)
                ->attach('file', file_get_contents($archivo->getRealPath()), $archivo->getClientOriginalName(), ['Content-Type' => $mime])
                ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/media", [
                    'messaging_product' => 'whatsapp',
                    'type' => $mime,
                ]);
        } catch (\Throwable $e) {
            return $this->errorEnvio($e->getMessage());
        }
        $mediaId = $subida->json('id');
        if (!$subida->successful() || !$mediaId) {
            return $this->errorEnvio($subida->body());
        }

        // 2. Enviar el mensaje con el archivo subido
        $nombreOriginal = mb_substr(basename($archivo->getClientOriginalName() ?: "archivo.{$ext}"), 0, 150);
        $objeto = ['id' => $mediaId];
        if ($caption && in_array($claveMeta, ['image', 'video', 'document'], true)) {
            $objeto['caption'] = $caption;
        }
        if ($claveMeta === 'document') {
            $objeto['filename'] = $nombreOriginal;
        }

        try {
            $envio = Http::withToken($token)->timeout(30)
                ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/messages", [
                    'messaging_product' => 'whatsapp',
                    'recipient_type' => 'individual',
                    'to' => $numeroCompleto,
                    'type' => $claveMeta,
                    $claveMeta => $objeto,
                ]);
        } catch (\Throwable $e) {
            return $this->errorEnvio($e->getMessage());
        }
        if (!$envio->successful()) {
            return $this->errorEnvio($envio->body());
        }
        $waMessageId = $envio->json('messages.0.id');

        // 3. Guardar el archivo con la misma convención que usa el webhook para los entrantes
        $disco = Storage::disk('public');
        $body = $caption;
        $metadata = ['from' => $phoneNumberId, 'id' => $waMessageId, 'timestamp' => time(), 'type' => $claveMeta];

        if ($claveMeta === 'image') {
            $this->guardarArchivo($disco, 'chats/img', "{$mediaId}.jpg", $archivo);
            $metadata['image'] = ['caption' => $caption, 'mime_type' => $mime, 'sha256' => null, 'id' => $mediaId];
        } elseif ($claveMeta === 'video') {
            $this->guardarArchivo($disco, 'chats/videos', "{$mediaId}.mp4", $archivo);
            $metadata['video'] = ['caption' => $caption, 'mime_type' => $mime, 'sha256' => null, 'id' => $mediaId];
        } elseif ($claveMeta === 'audio') {
            $ruta = "chats/audios/{$mediaId}.{$ext}";
            $this->guardarArchivo($disco, 'chats/audios', "{$mediaId}.{$ext}", $archivo);
            $body = url(Storage::url($ruta));
            $metadata['audio'] = ['mime_type' => $mime, 'id' => $mediaId];
        } else {
            $guardado = Str::random(8) . '_' . Str::slug(pathinfo($nombreOriginal, PATHINFO_FILENAME), '_') . ".{$ext}";
            $this->guardarArchivo($disco, 'chats/documentos', $guardado, $archivo);
            $metadata['document'] = [
                'caption' => $caption,
                'filename' => $guardado,
                'original_name' => $nombreOriginal,
                'mime_type' => $mime,
                'file_size' => $archivo->getSize(),
                'id' => $mediaId,
            ];
        }

        $mensaje = Mensaje::create([
            'contact_id' => $contacto->id,
            'wa_message_id' => $waMessageId,
            'wa_from' => $phoneNumberId,
            'wa_to' => $numeroCompleto,
            'type' => $tipoMsg,
            'body' => $body,
            'metadata' => $metadata,
            'estado' => Mensaje::ENVIADO,
            'sent_at' => now(),
        ]);

        Conversacion::updateOrCreate(
            ['contacto_id' => $contacto->id, 'phone_number_id' => $phoneNumberId],
            $this->datosConversacion($caption ? substr($caption, 0, 100) : '', $tipoMsg)
        );

        $this->difundirSalida($mensaje, $phoneNumberId);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $mensaje->id,
                'es mio' => true,
                'tipo' => $mensaje->type,
                'contenido' => $mensaje->body,
                'metadata' => $mensaje->metadata,
                'estado' => $mensaje->estado,
                'fecha' => $mensaje->created_at->toISOString(),
                'url_archivo' => $this->getFileUrl($mensaje),
            ],
        ]);
    }

    /**
     * MARCAR MENSAJES COMO LEÍDOS
     */
    public function markAsRead(Request $request, $numeroCompleto): JsonResponse
    {
        $user = $request->user();
        $phoneNumberId = $this->getPhoneNumberId($user);
        $whatsappApi = $this->getWhatsappApi($phoneNumberId);

        $mensajes = Mensaje::where('wa_to', $phoneNumberId)
            ->where('wa_from', $numeroCompleto)
            ->whereIn('estado', [Mensaje::ENTREGADO, Mensaje::ENVIADO])
            ->get();

        foreach ($mensajes as $mensaje) {
            $mensaje->update(['estado' => Mensaje::LEIDO]);
            if ($mensaje->wa_message_id) {
                $whatsappApi->markMessageAsRead($mensaje->wa_message_id);
            }
        }

        // Resetear contadores
        $contacto = Contacto::where('numero_completo', $numeroCompleto)->first();
        if ($contacto) {
            Conversacion::where('contacto_id', $contacto->id)
                ->where('phone_number_id', $phoneNumberId)
                ->update(['mensajes_no_leidos' => 0]);
        }

        return response()->json(['success' => true]);
    }

    // --- FUNCIONES AUXILIARES ---

    /** Cuenta (con el token) a la que pertenece el número; los secundarios están en whatsapp_numbers */
    private function cuentaDelNumero($phoneNumberId)
    {
        $cuenta = WhatsappAccount::where('phone_number_id', $phoneNumberId)->first()
            ?? WhatsappNumber::where('phone_number_id', $phoneNumberId)->first()?->cuenta;

        if (!$cuenta || !$cuenta->access_token) {
            throw new \Exception(__('Token de WhatsApp no configurado.'));
        }

        return $cuenta;
    }

    /** [tipo de mensaje, clave de Meta, tamaño máximo en MB] según el tipo MIME real del archivo (límites de Meta) */
    /**
     * MIME que Meta entiende. finfo suele devolver 'video/mp4' para un .m4a o 'application/octet-stream'
     * para videos del teléfono, así que para audio/video manda la extensión.
     */
    private function mimeNormalizado($archivo): string
    {
        $mime = strtolower((string) $archivo->getMimeType());
        $ext = strtolower($archivo->getClientOriginalExtension() ?: $archivo->guessExtension() ?: '');

        $porExtension = [
            'mp4' => 'video/mp4', 'm4v' => 'video/mp4', 'mov' => 'video/mp4', '3gp' => 'video/3gpp', '3gpp' => 'video/3gpp',
            'm4a' => 'audio/mp4', 'aac' => 'audio/aac', 'mp3' => 'audio/mpeg', 'amr' => 'audio/amr',
            'ogg' => 'audio/ogg', 'oga' => 'audio/ogg', 'opus' => 'audio/ogg',
        ];
        if (isset($porExtension[$ext]) && (str_starts_with($mime, 'audio/') || str_starts_with($mime, 'video/') || $mime === 'application/octet-stream')) {
            return $porExtension[$ext];
        }

        return $mime === 'audio/x-m4a' ? 'audio/mp4' : $mime;
    }

    private function claseDeArchivo(string $mime): ?array
    {
        if (in_array($mime, ['image/jpeg', 'image/png'], true)) {
            return [Mensaje::IMAGEN, 'image', 5];
        }
        if (in_array($mime, ['video/mp4', 'video/3gpp'], true)) {
            return [Mensaje::VIDEO, 'video', 16];
        }
        if (in_array($mime, ['audio/aac', 'audio/mp4', 'audio/mpeg', 'audio/amr', 'audio/ogg'], true)) {
            return [Mensaje::AUDIO, 'audio', 16];
        }
        if (in_array($mime, [
            'application/pdf', 'text/plain', 'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ], true)) {
            return [Mensaje::DOCUMENTO, 'document', 100];
        }

        return null;
    }

    /** Campos de la conversación tras un envío (tipo_ultimo_mensaje solo si la columna existe) */
    private function datosConversacion(string $texto, $tipo): array
    {
        static $tieneTipo = null;
        $tieneTipo ??= Schema::hasColumn('conversaciones', 'tipo_ultimo_mensaje');

        $datos = ['ultima_fecha' => now(), 'ultimo_mensaje' => $texto];
        if ($tieneTipo) {
            $datos['tipo_ultimo_mensaje'] = $tipo;
        }

        return $datos;
    }

    /** Respuesta de error legible para errores de Meta (ventana de 24 h, formato, etc.) */
    private function errorEnvio(string $detalle): JsonResponse
    {
        if (str_contains($detalle, '131047')) {
            return response()->json([
                'success' => false,
                'code' => 'ventana_24h',
                'message' => __('Pasaron más de 24 horas desde el último mensaje del cliente. Solo puedes enviarle una plantilla aprobada.'),
            ], 422);
        }
        if (str_contains($detalle, '131051')) {
            return response()->json(['success' => false, 'message' => __('WhatsApp no admite este tipo de archivo.')], 422);
        }
        if (preg_match('/"code"\s*:\s*190\b/', $detalle)) {
            return response()->json(['success' => false, 'message' => __('La conexión con WhatsApp venció. Pide al administrador que reconecte la cuenta.')], 502);
        }

        \Illuminate\Support\Facades\Log::warning('Error enviando mensaje móvil a Meta', ['detalle' => mb_substr($detalle, 0, 500)]);

        $motivo = null;
        if (preg_match('/"(?:error_user_msg|message)"\s*:\s*"((?:[^"\\\\]|\\\\.)*)"/', $detalle, $m)) {
            $motivo = stripcslashes($m[1]);
        }

        return response()->json([
            'success' => false,
            'message' => __('No se pudo enviar el mensaje por WhatsApp.') . ($motivo ? ' (' . mb_substr($motivo, 0, 180) . ')' : ''),
        ], 502);
    }

    /**
     * Guarda el archivo en el disco público. putFileAs devuelve false sin avisar si la carpeta
     * no es escribible (p. ej. creada por el worker con otro usuario), así que se comprueba y se deja registro.
     */
    private function guardarArchivo($disco, string $dir, string $nombre, $archivo): void
    {
        $ok = $disco->putFileAs($dir, $archivo, $nombre);
        if (! $ok) {
            $ok = $disco->put("{$dir}/{$nombre}", file_get_contents($archivo->getRealPath()));
        }
        if (! $ok) {
            \Illuminate\Support\Facades\Log::error('No se pudo guardar el archivo del chat móvil (revisa permisos de storage/app/public)', ['ruta' => "{$dir}/{$nombre}"]);
        }
    }

    /** Emite el mensaje saliente al chat web y a la app (mismo evento que usa el chat web) */
    private function difundirSalida(Mensaje $mensaje, string $phoneNumberId): void
    {
        try {
            $mensaje->load('contacto');
            if ($mensaje->contacto) {
                $mensaje->nombre_completo = $mensaje->contacto->nombre_completo;
            }
            broadcast(new MensajeSent($mensaje, $phoneNumberId));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('No se pudo difundir el mensaje saliente', ['error' => $e->getMessage()]);
        }
    }

    /** Texto del último mensaje para la lista de chats */
    private function etiquetaUltimo($tipo, $body): string
    {
        return match ((int) $tipo) {
            Mensaje::IMAGEN => '📷 Imagen',
            Mensaje::VIDEO => '🎬 Video',
            Mensaje::AUDIO => '🎤 Audio',
            Mensaje::DOCUMENTO => '📄 Documento',
            Mensaje::LOCALIZACION => '📍 Ubicación',
            default => (string) $body,
        };
    }

    private function parseLastMessage($mensaje)
    {
        if (!$mensaje) return '';

        if ($mensaje->tipo_ultimo_mensaje == Mensaje::IMAGEN) return '📷 Imagen';
        if ($mensaje->tipo_ultimo_mensaje == Mensaje::VIDEO) return '🎬 Video';
        if ($mensaje->tipo_ultimo_mensaje == Mensaje::AUDIO) return '🎤 Audio';
        if ($mensaje->tipo_ultimo_mensaje == Mensaje::DOCUMENTO) return '📄 Documento';

        return $mensaje->ultimo_mensaje ?? '';
    }

    private function getFileUrl($mensaje)
    {
        if (!is_array($mensaje->metadata)) {
            return null;
        }

        if (in_array($mensaje->type, [Mensaje::IMAGEN, Mensaje::VIDEO, Mensaje::DOCUMENTO, Mensaje::AUDIO])) {
            $metadata = $mensaje->metadata;

            if ($mensaje->type == Mensaje::IMAGEN) {
                if (isset($metadata['image']['id'])) {
                    // Usamos Storage::url directamente sin la función url() de Laravel
                    return Storage::url("chats/img/{$metadata['image']['id']}.jpg");
                }
                // Si no hay ID, intenta con el body directo (a veces WA envía la URL ahí)
                return filter_var($mensaje->body, FILTER_VALIDATE_URL) ? $mensaje->body : null;
            }

            if ($mensaje->type == Mensaje::VIDEO) {
                if (isset($metadata['video']['id'])) {
                    return Storage::url("chats/videos/{$metadata['video']['id']}.mp4");
                }
                return filter_var($mensaje->body, FILTER_VALIDATE_URL) ? $mensaje->body : null;
            }

            if ($mensaje->type == Mensaje::DOCUMENTO) {
                if (isset($metadata['document']['filename'])) {
                    $nombre = basename($metadata['document']['filename']);
                    return Storage::url("chats/documentos/$nombre");
                }
                return filter_var($mensaje->body, FILTER_VALIDATE_URL) ? $mensaje->body : null;
            }

            if ($mensaje->type == Mensaje::AUDIO) {
                return filter_var($mensaje->body, FILTER_VALIDATE_URL) || str_starts_with((string) $mensaje->body, '/') ? $mensaje->body : null;
            }
        }
        return null;
    }
}
