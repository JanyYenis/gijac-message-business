<?php

namespace App\Jobs;

use App\Models\WhatsappAccount;
use App\Models\Contacto;
use App\Models\Empresa;
use App\Models\Usuario;
use App\Services\Contactos\ContactoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use libphonenumber\PhoneNumberUtil;

class WebhookContacts implements ShouldQueue
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
        $this->contacts($this->datos, $this->waba_id);
    }

    public function contacts($datos, $waba_id)
    {
        $config = WhatsappAccount::where('estado', WhatsappAccount::CONECTADO)->where('waba_id', $waba_id)->first();
        $nombre = $datos['contacts'][0]['profile']['name'] ?? 'Sin nombre';
        $telefono = $datos['contacts'][0]['wa_id'];
        $contacto = Contacto::whereRaw("numero_completo = ?", [$telefono])?->first() ?? null;
        if (!$contacto) {
            $empresa = Empresa::find($config->cod_empresa);
            $usuario = Usuario::where('uuid', $empresa->cod_usuario)->first();
            if (!$empresa) {
                return;
            }

            try {

                $contacto = app(ContactoService::class)->crearAutomaticamente(
                    empresa: $empresa,
                    telefono: $datos['contacts'][0]['wa_id'],
                    nombre: $nombre,
                    uuid: $empresa->cod_usuario,
                    esDemo: $usuario?->demo ?? false
                );

            } catch (\RuntimeException $e) {

                // Aquí decides qué hacer cuando se alcanzó el límite.
                // Por ejemplo, registrar log y no crear el contacto.
                Log::warning('No se pudo crear contacto automáticamente', [
                    'telefono' => $datos['contacts'][0]['wa_id'],
                    'empresa' => $empresa->id,
                    'mensaje' => $e->getMessage(),
                ]);
            }
        }
    }
}
