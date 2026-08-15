<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MensajeSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $mensaje;
    public $phone_number_id;

    /**
     * Create a new event instance.
     */
    public function __construct($mensaje, $phone_number_id = null)
    {
        $this->mensaje = $mensaje;
        $this->phone_number_id = $phone_number_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        if ($this->phone_number_id) {
            return [
                new PresenceChannel('chat.'.$this->phone_number_id),
            ];
        }
        return [
            new PresenceChannel('chat.'.$this->mensaje->wa_to),
        ];
    }

    public function broadcastAs(): string
    {
        return 'mensaje.enviado';
    }
}
