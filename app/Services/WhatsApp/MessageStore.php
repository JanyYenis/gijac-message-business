<?php

namespace App\Services\WhatsApp;

use App\DTO\ParsedWhatsAppMessage;
use App\Events\MensajeSent;
use App\Models\Contacto;
use App\Models\EnvioCampana;
use App\Models\Mensaje;
use Carbon\Carbon;

class MessageStore
{
    public function guardarEntrante(array $mensajeData, ParsedWhatsAppMessage $parsed, string $waFrom, ?string $waTo, string $waMsgId, string $estado, Carbon $sentAt, ?int $contactId): Mensaje
    {
        return Mensaje::updateOrCreate(['wa_message_id' => $waMsgId], [
            'wa_from' => $waFrom,
            'wa_to' => $waTo,
            'type' => $parsed->tipo,
            'contact_id' => $contactId,
            'body' => $parsed->body,
            'metadata' => $mensajeData,
            'estado' => $estado,
            'sent_at' => $sentAt,
        ]);
    }

    public function marcarAperturaCampana(string $waMsgId): void
    {
        EnvioCampana::where('wamid', $waMsgId)
            ->update(['apertura' => EnvioCampana::ABIERTO, 'fecha_apertura' => now()]);
    }

    public function difundir(string $waMsgId, ?Contacto $contacto): void
    {
        $mensaje = Mensaje::where('wa_message_id', $waMsgId)->first();
        if (!$mensaje) return;

        if ($contacto) {
            $mensaje->nombre_completo = $contacto->nombre_completo;
        }

        broadcast(new MensajeSent($mensaje));
    }
}
