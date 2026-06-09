<?php

namespace App\Jobs;

use App\Models\ConfiguracionMeta;
use App\Models\Contacto;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
        $contacto = Contacto::whereRaw("CONCAT(codigo_telefono, telefono) = ?", [$telefono])?->first() ?? null;
        if (!$contacto) {
            if (!str_starts_with($telefono, '+')) {
                $telefono = '+' . $telefono;
            }
            $phoneUtil = PhoneNumberUtil::getInstance();
            $parsedNumber = $phoneUtil->parse($telefono, null);
             // Obtener el código del país
            $countryCode = $parsedNumber->getCountryCode();

            // Obtener el resto del número sin el código del país
            $nationalNumber = $parsedNumber->getNationalNumber();
            Contacto::create([
                'nombre' => $nombre,
                'codigo_telefono' => $countryCode,
                'telefono' => $nationalNumber,
                'uuid' => $config->uuid,
            ]);
        }
    }
}
