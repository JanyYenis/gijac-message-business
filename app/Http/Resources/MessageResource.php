<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    private static $phoneNumberId = null;

    public static function setPhoneNumberId($phoneNumberId)
    {
        self::$phoneNumberId = $phoneNumberId;
    }

    public function toArray($request)
    {
        // Usar el phoneNumberId pasado o intentar obtenerlo del request
        $phoneNumberId = self::$phoneNumberId ?? $request->get('phone_number_id');

        return [
            'id' => $this->id,
            'conversation_id' => $this->determineConversationId($phoneNumberId),
            'sender_id' => $this->wa_from,
            'content' => $this->body,
            'type' => $this->type ?? 'text',
            'status' => $this->mapStatus($this->estado),
            'sent_at' => $this->created_at->toIso8601String(),
            'read_at' => $this->estado == \App\Models\Mensaje::LEIDO
                ? $this->updated_at->toIso8601String()
                : null,
            'wamid' => $this->wamid,
            'is_from_me' => $phoneNumberId ? $this->wa_from === $phoneNumberId : false,
        ];
    }

    private function determineConversationId($phoneNumberId)
    {
        if (!$phoneNumberId) {
            return $this->wa_from;
        }

        return $this->wa_from === $phoneNumberId ? $this->wa_to : $this->wa_from;
    }

    private function mapStatus($estado)
    {
        return match($estado) {
            \App\Models\Mensaje::ENVIADO => 'sent',
            \App\Models\Mensaje::ENTREGADO => 'delivered',
            \App\Models\Mensaje::LEIDO => 'read',
            default => 'sent',
        };
    }
}
