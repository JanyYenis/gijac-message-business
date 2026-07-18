<?php

namespace App\Http\Controllers\Apis\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Models\Conversacion;
use App\Models\Mensaje;
use App\Models\ConfiguracionMeta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;

class ChatController extends Controller
{
    // Obtener el phone_number_id dinámicamente basado en la empresa del usuario
    private function getPhoneNumberId($user)
    {
        $config = ConfiguracionMeta::where('cod_empresa', $user->cod_empresa)
            ->where('estado', ConfiguracionMeta::ACTIVO)
            ->first();

        if (!$config) {
            throw new \Exception('No hay configuración de WhatsApp para esta empresa.');
        }

        return $config->phone_number_id;
    }

    // Instanciar la API de WhatsApp dinámicamente (asumiendo que tienes un helper o método en ConfiguracionMeta)
    private function getWhatsappApi($phoneNumberId)
    {
        $config = ConfiguracionMeta::where('phone_number_id', $phoneNumberId)->first();
        if (!$config || !$config->token) {
            throw new \Exception('Token de WhatsApp no configurado.');
        }

        // Ajusta esto según cómo instancies tu API en tu proyecto
        return new \Netflie\WhatsAppCloudApi\WhatsAppCloudApi([
            'from_phone_number_id' => $phoneNumberId,
            'access_token' => $config->token,
            'version' => $config->version, // O la versión que uses
        ]);
    }

    /**
     * LISTA DE CHATS (Contactos con última conversación)
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $phoneNumberId = $this->getPhoneNumberId($user);

        $contactos = Contacto::query()
            ->leftJoin('conversaciones', function ($join) use ($phoneNumberId) {
                $join->on('contactos.id', '=', 'conversaciones.contacto_id')
                    ->where('conversaciones.phone_number_id', $phoneNumberId);
            })
            ->with(['conversacion' => function ($q) use ($phoneNumberId) {
                $q->where('phone_number_id', $phoneNumberId);
            }])
            ->where('contactos.estado', Contacto::ACTIVO)
            ->orderByRaw('conversaciones.ultima_fecha IS NULL, conversaciones.ultima_fecha DESC')
            ->select('contactos.*')
            ->get();

        $chats = $contactos->map(function ($contacto) {
            // SEGURO: Si no hay conversación, esto será null. Si hay, obtiene la primera.
            $conversacion = $contacto->conversacion ? $contacto->conversacion->first() : null;

            return [
                'id' => $contacto->numero_completo,
                'contact_id' => $contacto->id,
                'nombre' => trim(($contacto->nombre ?? '') . ' ' . ($contacto->apellido ?? '')),
                'telefono' => $contacto->telefono,
                'codigo_telefono' => $contacto->codigo_telefono,
                'numero_completo' => $contacto->numero_completo,
                // Si es null, devuelve 'Sin mensajes'. Si tiene datos, los parsea.
                'ultimo_mensaje' => $conversacion ? $this->parseLastMessage($conversacion) : '',
                // Si es null, devuelve null. Si tiene, envía la fecha cruda.
                'hora_ultimo_mensaje' => $conversacion ? $conversacion->ultima_fecha : null,
                // Si es null, devuelve 0. Si tiene, envía los no leídos.
                'no_leidos' => $conversacion ? $conversacion->mensajes_no_leidos : 0,
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
            return response()->json(['success' => false, 'message' => 'Contacto no encontrado'], 404);
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
        if ($before_id) {
            $query->where('id', '<', $before_id);
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
            }),
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
            return response()->json(['success' => false, 'message' => 'Contacto no encontrado'], 404);
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
            return response()->json(['success' => false, 'message' => 'Error al enviar por WhatsApp: ' . $e->getMessage()], 500);
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
            [
                'ultima_fecha' => now(),
                'ultimo_mensaje' => substr($mensajeTexto, 0, 100),
            ]
        );

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
        if (in_array($mensaje->type, [Mensaje::IMAGEN, Mensaje::VIDEO, Mensaje::DOCUMENTO])) {
            $metadata = json_decode($mensaje->metadata);
            // Asumiendo que guardaste el path en storage
            // Ajusta esto según cómo guardes tus archivos
            if ($mensaje->type == Mensaje::IMAGEN) {
                return isset($metadata->image->id) ? url(Storage::url("chats/img/{$metadata->image->id}.jpg")) : null;
            }
            if ($mensaje->type == Mensaje::DOCUMENTO) {
                return isset($metadata->document->id) ? url(Storage::url("chats/documentos/{$metadata->document->id}")) : null;
            }
            if ($mensaje->type == Mensaje::AUDIO) {
                return $mensaje->body; // En tu controlador original guardabas la URL directa en body para audios
            }
        }
        return null;
    }
}
