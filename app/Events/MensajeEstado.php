<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Cambio de estado (enviado / entregado / leído / fallido) de un mensaje saliente.
 * Va por el mismo canal que los mensajes (chat.{phone_number_id}) para que la app móvil lo reciba.
 */
class MensajeEstado implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $mensaje;

    public function __construct($mensaje)
    {
        $this->mensaje = $mensaje;
    }

    public function broadcastOn(): array
    {
        // En los mensajes salientes wa_from es el número de la empresa
        return [new PresenceChannel('chat.' . $this->mensaje->wa_from)];
    }

    public function broadcastAs(): string
    {
        return 'mensaje.estado';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->mensaje->id,
            'wa_message_id' => $this->mensaje->wa_message_id,
            'estado' => $this->mensaje->estado,
            'wa_to' => $this->mensaje->wa_to,
        ];
    }
}
