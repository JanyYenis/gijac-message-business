<?php

namespace App\Events;

use App\Models\Mensaje;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
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

    /**
     * Mismos datos de siempre (mensaje y phone_number_id) + el contador de no leídos del chat,
     * para que la app actualice la lista sin tener que consultarla de nuevo.
     */
    public function broadcastWith(): array
    {
        return [
            'mensaje' => $this->mensaje,
            'phone_number_id' => $this->phone_number_id,
            'no_leidos' => $this->contarNoLeidos(),
        ];
    }

    private function contarNoLeidos(): ?int
    {
        try {
            $propio = (string) ($this->phone_number_id ?? $this->mensaje->wa_to);
            $contacto = (string) $this->mensaje->wa_from === $propio
                ? $this->mensaje->wa_to
                : $this->mensaje->wa_from;

            return Mensaje::where('wa_from', $contacto)
                ->where('wa_to', $propio)
                ->whereIn('estado', [Mensaje::ENTREGADO, Mensaje::ENVIADO])
                ->count();
        } catch (\Throwable $e) {
            return null;
        }
    }
}
