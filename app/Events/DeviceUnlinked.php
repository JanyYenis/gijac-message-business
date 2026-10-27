<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

/** Se desconectó un dispositivo (desde la web o cerrando sesión en la app) */
class DeviceUnlinked implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets;

    public function __construct(public string $usuarioUuid)
    {
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('dispositivos.' . $this->usuarioUuid)];
    }

    public function broadcastAs(): string
    {
        return 'device.unlinked';
    }

    public function broadcastWith(): array
    {
        return [];
    }
}
