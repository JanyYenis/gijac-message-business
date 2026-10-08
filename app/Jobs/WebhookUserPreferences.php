<?php

namespace App\Jobs;

use App\Models\Contacto;
use App\Models\PreferenciaContacto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WebhookUserPreferences implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $datos;
    protected $waba_id;

    /**
     * Create a new job instance.
     */
    public function __construct($datos, $waba_id)
    {
        $this->datos = $datos;
        $this->waba_id = $waba_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->user_preferences($this->datos, $this->waba_id);
    }

    public function user_preferences($datos, $waba_id)
    {
        $telefono = $datos['user_preferences'][0]['wa_id'];
        $contacto = Contacto::whereRaw("numero_completo = ?", [$telefono])?->first() ?? null;
        if ($contacto) {
            PreferenciaContacto::create([
                'cod_contacto' => $contacto?->id,
                'telefono' => $datos['user_preferences'][0]['wa_id'] ?? null,
                'detalle' => $datos['user_preferences'][0]['detail'] ?? null,
                'categoria' => $datos['user_preferences'][0]['category'] ?? null,
                'valor' => $datos['user_preferences'][0]['value'] ?? null,
            ]);
            $contacto->update(['preferencia' => $datos['user_preferences'][0]['value'] ?? null]);
        }
    }
}
