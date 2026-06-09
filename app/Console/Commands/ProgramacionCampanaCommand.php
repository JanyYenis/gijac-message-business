<?php

namespace App\Console\Commands;

use App\Exceptions\ErrorException;
use App\Jobs\SendWhatsAppMessage;
use App\Models\ConfiguracionMeta;
use App\Models\VariableCampana;
use App\Models\Campana;
use App\Models\EventoDetalle;
use App\Models\Mensaje;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Netflie\WhatsAppCloudApi\Message\Template\Component;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;

class ProgramacionCampanaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campana:programadas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando esta diseñado para el disparo de campañas que se encuentren en estado pendiente para poder hacre el envio automatico.';

    public $version;
    public $waba_id;
    public $app_id;
    public $phone_number_id	;
    public $token;
    public $numeroG;
    public $whatsapp_cloud_api;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $this->iniciar();
        $this->campanas();
    }

    public function iniciar($id)
    {
        $config = ConfiguracionMeta::Where('estado', ConfiguracionMeta::ACTIVO)
            ->where('uuid', $id)
            ->first() ?? null;

        if ($config) {
            $this->version = $config->version;
            $this->waba_id = $config->waba_id;
            $this->app_id = $config->app_id;
            $this->phone_number_id = $config->phone_number_id;
            $this->token = $config->token;
            $this->numeroG = $config?->numero ?? '573161542681';

            $this->whatsapp_cloud_api = new WhatsAppCloudApi([
                'from_phone_number_id' => $this->phone_number_id,
                'access_token' => $this->token,
                'graph_version' => $this->version,
            ]);

            return true;
        }

        return false;
    }

    public function campanas()
    {
        $fechaActual = Carbon::now();
        $campanas = Campana::with(
                'enviosActivos.contacto',
            )->where('estado', Campana::PENDIENTE)
            ->whereDate('fecha_envio', $fechaActual)
            ->get();

        foreach ($campanas as $campana) {
            $tieneConfiguracion = $this->iniciar($campana->uuid);
            if ($tieneConfiguracion) {
                // Convertir el campo de tipo timestamp a un objeto Carbon
                $horaCampana = Carbon::parse($campana->fecha_envio);

                // Validar si la hora del evento es menor que la hora actual
                if ($horaCampana->lt($fechaActual)) {
                    $envios_campana = $campana?->enviosActivos ?? [];
                    if (count($envios_campana)) {
                        $campana->update(['estado' => Campana::ENVIADO]);
                        $url = "https://graph.facebook.com/{$this->version}/{$campana?->id_plantilla}";
                        $metodo = 'GET';
                        $responseP = consultaBase($url, $metodo, $this->token);
                        $plantilla = json_decode($responseP);
                        $variables = [];
                        if ($campana?->contenido_multimedia && ($campana?->tipo == Campana::IMAGEN ||
                            $campana?->tipo == Campana::VIDEO || $campana?->tipo == Campana::DOCUMENTO)) {
                            $variables['file'] = $campana?->contenido_multimedia ?? '';
                        }
                        $datosCampana = VariableCampana::where('cod_campana', $campana->id)
                            ->where('tipo', VariableCampana::TEXT)
                            ->where('estado', VariableCampana::ACTIVO)
                            ->get();

                        $nombres_variables = [];
                        foreach ($datosCampana as $value) {
                            $nombres_variables[] = $value?->numero;
                            $variables['variables'][$value?->numero] = $value->valor;
                        }

                        $datosCampanaHeader = VariableCampana::where('cod_campana', $campana->id)
                            ->where('tipo', VariableCampana::HEADER)
                            ->where('estado', VariableCampana::ACTIVO)
                            ->first() ?? null;

                        if ($datosCampanaHeader) {
                            $variables['header_text'] = $datosCampanaHeader->valor;
                        }

                        foreach ($envios_campana as $envio_c) {
                            dispatch(new SendWhatsAppMessage($envio_c?->contacto, $plantilla, $variables, $campana->id, $campana->uuid, $nombres_variables));
                        }
                    } else {
                        $campana->update(['estado' => Campana::CANCELADO]);
                    }
                }
            }
        }
    }
}
