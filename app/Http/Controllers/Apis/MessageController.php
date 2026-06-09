<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Models\Contacto;
use App\Models\Mensaje;
use App\Models\ConfiguracionMeta;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MessageController extends Controller
{
    /**
     * Obtener mensajes de una conversación con un contacto
     * GET /api/conversations/{contactId}/messages
     */
    public function index(Request $request, $contactId)
    {
        $offset = $request->input('offset', 0);
        $limit = $request->input('limit', 50);

        // Buscar contacto
        $contacto = Contacto::where('id', $contactId)
            ->where('estado', Contacto::ACTIVO)
            ->firstOrFail();

        $config = ConfiguracionMeta::where('uuid', $request->user()->uuid)->firstOrFail();
        $phoneNumberId = $config->phone_number_id;
        $numeroCompleto = $contacto->codigo_telefono . $contacto->telefono;

        // Traer mensajes entre el contacto y el número de la empresa
        $mensajes = Mensaje::where(function ($query) use ($phoneNumberId, $numeroCompleto) {
                // Mensajes enviados por la empresa al contacto
                $query->where(function ($q) use ($phoneNumberId, $numeroCompleto) {
                    $q->where('wa_from', $phoneNumberId)
                    ->where('wa_to', $numeroCompleto);
                })
                // O mensajes enviados por el contacto a la empresa
                ->orWhere(function ($q) use ($phoneNumberId, $numeroCompleto) {
                    $q->where('wa_from', $numeroCompleto)
                    ->where('wa_to', $phoneNumberId);
                });
            })
            ->orderBy('created_at', 'asc')
            ->skip($offset)
            ->take($limit)
            ->get();

        // Marcar mensajes del contacto como leídos
        $this->markMessagesAsRead($phoneNumberId, $numeroCompleto);

        // Pasar el phoneNumberId al resource
        return MessageResource::collection($mensajes)
            ->additional([
                'meta' => [
                    'contact_id' => $contacto->id,
                    'contact_name' => $contacto->nombre . ' ' . $contacto->apellido,
                    'phone_number_id' => $phoneNumberId, // <-- Agregar esto
                    'offset' => $offset,
                    'limit' => $limit,
                    'total' => $mensajes->count(),
                ]
            ]);
    }

    /**
     * Enviar un mensaje
     * POST /api/conversations/{contactId}/messages
     */
    public function store(Request $request, $contactId)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:4096',
            'type' => 'nullable|string|in:text,image,video,audio,document',
        ]);

        // Buscar contacto
        $contacto = Contacto::where('id', $contactId)
            ->where('estado', Contacto::ACTIVO)
            ->firstOrFail();

        $config = ConfiguracionMeta::where('uuid', $request->user()->uuid)->firstOrFail();
        $phoneNumberId = $config->phone_number_id;
        $numeroCompleto = $contacto->codigo_telefono . $contacto->telefono;

        // Crear el mensaje
        $mensaje = Mensaje::create([
            'wa_from' => $phoneNumberId,
            'wa_to' => $numeroCompleto,
            'body' => $validated['content'],
            'type' => $validated['type'] ?? 'text',
            'estado' => Mensaje::ENVIADO,
            'wamid' => 'wamid_' . uniqid() . '_' . time(), // ID temporal
            'created_at' => now(),
        ]);

        // Aquí deberías agregar la lógica para enviar el mensaje real por WhatsApp API
        // Por ahora solo lo guardamos en la BD

        return (new MessageResource($mensaje))->additional([
            'message' => 'Mensaje enviado exitosamente'
        ]);
    }

    /**
     * Marcar mensajes como leídos
     * PUT /api/messages/mark-read
     */
    public function markAsRead(Request $request)
    {
        $validated = $request->validate([
            'contact_id' => 'required|exists:contactos,id',
        ]);

        $contacto = Contacto::findOrFail($validated['contact_id']);
        $config = ConfiguracionMeta::where('uuid', $request->user()->uuid)->firstOrFail();
        $phoneNumberId = $config->phone_number_id;
        $numeroCompleto = $contacto->codigo_telefono . $contacto->telefono;

        $this->markMessagesAsRead($phoneNumberId, $numeroCompleto);

        return response()->json([
            'message' => 'Mensajes marcados como leídos'
        ]);
    }

    /**
     * Obtener estado de contacto (últimas 24h)
     * GET /api/conversations/{contactId}/status
     */
    public function contactStatus(Request $request, $contactId)
    {
        $contacto = Contacto::where('id', $contactId)
            ->where('estado', Contacto::ACTIVO)
            ->firstOrFail();

        $config = ConfiguracionMeta::where('uuid', $request->user()->uuid)->firstOrFail();
        $phoneNumberId = $config->phone_number_id;
        $numeroCompleto = $contacto->codigo_telefono . $contacto->telefono;

        // Estado del contacto (últimos 24h)
        $mensajesRecientes = Mensaje::where(function ($query) use ($phoneNumberId, $numeroCompleto) {
                $query->where(function ($q) use ($phoneNumberId, $numeroCompleto) {
                    $q->where('wa_from', $phoneNumberId)
                      ->where('wa_to', $numeroCompleto);
                })
                ->orWhere(function ($q) use ($phoneNumberId, $numeroCompleto) {
                    $q->where('wa_from', $numeroCompleto)
                      ->where('wa_to', $phoneNumberId);
                });
            })
            ->where('created_at', '>=', now()->subHours(24))
            ->count();

        $ultimoMensaje = Mensaje::where(function ($query) use ($phoneNumberId, $numeroCompleto) {
                $query->where(function ($q) use ($phoneNumberId, $numeroCompleto) {
                    $q->where('wa_from', $phoneNumberId)
                      ->where('wa_to', $numeroCompleto);
                })
                ->orWhere(function ($q) use ($phoneNumberId, $numeroCompleto) {
                    $q->where('wa_from', $numeroCompleto)
                      ->where('wa_to', $phoneNumberId);
                });
            })
            ->orderByDesc('created_at')
            ->first();

        return response()->json([
            'contact_id' => $contacto->id,
            'contact_name' => $contacto->nombre . ' ' . $contacto->apellido,
            'is_online' => false, // Esto dependerá de tu lógica
            'last_seen' => $ultimoMensaje?->created_at,
            'messages_last_24h' => $mensajesRecientes,
        ]);
    }

    /**
     * Método privado para marcar mensajes como leídos
     */
    private function markMessagesAsRead($phoneNumberId, $numeroCompleto)
    {
        Mensaje::where('wa_from', $numeroCompleto)
            ->where('wa_to', $phoneNumberId)
            ->whereIn('estado', [Mensaje::ENVIADO, Mensaje::ENTREGADO])
            ->update([
                'estado' => Mensaje::LEIDO,
                'updated_at' => now()
            ]);
    }
}
