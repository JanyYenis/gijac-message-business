<?php

namespace App\Observers;

use App\Models\ConfiguracionMeta;
use App\Models\Conversacion;
use App\Models\Mensaje;
use Illuminate\Support\Facades\DB;

class MensajeObserver
{
    /**
     * Handle the mensaje "created" event.
     */
    public function created(Mensaje $mensaje): void
    {
        $this->actualizarConversacion($mensaje);
    }

    /**
     * Handle the mensaje "updated" event.
     */
    public function updated(Mensaje $mensaje): void
    {
        $this->actualizarConversacion($mensaje);
    }

    /**
     * Handle the mensaje "deleted" event.
     */
    public function deleted(Mensaje $mensaje): void
    {
        //
    }

    /**
     * Handle the mensaje "restored" event.
     */
    public function restored(Mensaje $mensaje): void
    {
        //
    }

    /**
     * Handle the mensaje "force deleted" event.
     */
    public function forceDeleted(Mensaje $mensaje): void
    {
        //
    }

    public function actualizarConversacion(Mensaje $mensaje)
    {
        if ($mensaje->contact_id) {
            $config = ConfiguracionMeta::whereIn('phone_number_id', [$mensaje?->wa_from, $mensaje?->wa_to])
                ->first() ?? null;

            Conversacion::updateOrCreate(
                [
                    'contacto_id' => $mensaje?->contact_id,
                    'phone_number_id' => $config->phone_number_id,
                ],
                [
                    'ultimo_mensaje' => $mensaje->body,
                    'tipo_ultimo_mensaje' => $mensaje->type,
                    'ultima_fecha' => $mensaje->created_at,
                    'mensajes_no_leidos' => DB::raw('mensajes_no_leidos + 1'),
                ]
            );
        }
    }
}
