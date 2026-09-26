<?php

namespace App\Jobs;

use App\Models\ConfiguracionMeta;
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
        $this->contacts($this->datos, $this->app_id);
    }

    public function contacts($datos, $app_id)
    {
        $config = ConfiguracionMeta::where('estado', ConfiguracionMeta::ACTIVO)->where('app_id', $app_id)->first();
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
