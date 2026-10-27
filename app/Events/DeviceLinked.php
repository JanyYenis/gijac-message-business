<?php

namespace App\Events;

use App\Models\Dispositivo;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/** Un teléfono escaneó el QR: la web pasa a mostrar la sesión con los datos reales */
class DeviceLinked implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Dispositivo $dispositivo)
    {
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('dispositivos.' . $this->dispositivo->usuario_id)];
    }

    public function broadcastAs(): string
    {
        return 'device.linked';
    }

    public function broadcastWith(): array
    {
        $d = $this->dispositivo;

        return [
            'id' => $d->id,
            'nombre_dispositivo' => $d->nombre_dispositivo,
            'modelo' => $d->modelo,
            'sistema_operativo' => $d->sistema_operativo,
            'version_so' => $d->version_so,
            'ip' => $d->ip,
            'vinculado_en' => optional($d->vinculado_en)->format('d/m/Y h:i A'),
        ];
    }
}
