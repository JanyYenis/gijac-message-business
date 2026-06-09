<?php

namespace App\Jobs;

use App\Events\MensajeLeido;
use App\Models\DetalleErrorMensaje;
use App\Models\EnvioCampana;
use App\Models\Mensaje;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WebhookStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $datos;
    protected $app_id;

    /**
     * Create a new job instance.
     */
    public function __construct($datos, $app_id)
    {
        $this->datos = $datos;
        $this->app_id = $app_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->status($this->datos, $this->app_id);
    }

    public function status($datos, $app_id)
    {
        $tipo = 'text';
        $waFrom = $datos['statuses'][0]['recipient_id'] ?? '111';
        $waTo = $datos['metadata']['display_phone_number'];
        $mensaje = $datos['entry'][0]['changes'][0]['field'] ?? 'James';
        $idMensaje = $datos['statuses'][0]['id'] ?? 'Que pasa';
        $estado = Mensaje::DATOS_ESTADO[$datos['statuses'][0]['status'] ?? 'sent'];
        // Tipo
        $tipoMapped = Mensaje::DATOS_TIPO[$tipo] ?? (
            $tipo === 'interactive' ? null : Mensaje::TEXTO
        );

        $validarMensajeEnviado = Mensaje::firstWhere('wa_message_id', $idMensaje);
        if ($validarMensajeEnviado) {
            $actualizarvalidarMensajeEnviado = $validarMensajeEnviado->update(["estado" => $estado]);
        } else {
            if (isset($datos['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'])) {
                $mensaje = $datos['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'] ?? 'James';
            }
            $validarMensajeEnviado = Mensaje::updateOrCreate([
                'wa_message_id' => $idMensaje
            ], [
                'wa_from' => $waFrom,
                'wa_to'   => $waTo,
                'type'    => $tipoMapped,
                'body'    => $mensaje,
                "estado"  => $estado,
            ]);
        }

        if ($estado == Mensaje::FALLIDO) {
            DetalleErrorMensaje::create([
                'wamid' => $datos['statuses'][0]['id'],
                'code' => $datos['statuses'][0]['errors'][0]['code'],
                'title' => $datos['statuses'][0]['errors'][0]['title'],
                'message' => $datos['statuses'][0]['errors'][0]['message'],
                'details' => $datos['statuses'][0]['errors'][0]['error_data']['details'],
            ]);
        }
        // -------------------------------------------------------------------------------------------

        if ($estado == Mensaje::LEIDO) {
            EnvioCampana::where('wamid', $idMensaje)->update(['apertura' => EnvioCampana::ABIERTO, 'fecha_apertura' => Carbon::now()]);
        }
        broadcast(new MensajeLeido($validarMensajeEnviado));
    }
}
