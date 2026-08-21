<?php

namespace App\Events;

use App\Models\Dispositivo;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeviceLinked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Dispositivo $dispositivo)
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('dispositivos.' . $this->dispositivo->usuario_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'device.linked';
    }

    public function broadcastWith(): array
    {
        return [
            'nombre_dispositivo' => $this->dispositivo->nombre_dispositivo,
            'sistema_operativo' => $this->dispositivo->sistema_operativo,
            'ip' => $this->dispositivo->ip,
            'vinculado_en' => $this->dispositivo->vinculado_en->diffForHumans(),
        ];
    }
}
