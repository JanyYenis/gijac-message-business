<?php

namespace App\Jobs;

use App\Models\ConfiguracionMeta;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;

class SubirFotoPerfilWhatsApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    protected $imagen;
    protected $userId;
    public $version;
    public $waba_id;
    public $app_id;
    public $phone_number_id	;
    public $token;
    public $numeroG;
    public $whatsapp_cloud_api;

    /**
     * Create a new job instance.
     */
    public function __construct($userId, $imagen, $url)
    {
        $this->userId = $userId;
        $this->imagen = $imagen;
        $this->url = $url;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $config = ConfiguracionMeta::where('estado', ConfiguracionMeta::ACTIVO)->where('cod_usuario', $this->userId)->first();
        $this->version = $config?->version ?? null;
        $this->waba_id = $config?->waba_id ?? null;
        $this->app_id = $config?->app_id ?? null;
        $this->phone_number_id = $config?->phone_number_id ?? null;
        $this->token = $config?->token ?? null;
        $this->numeroG = $config?->numero ?? '573000000000';

        if ($this->phone_number_id && $this->token && $this->version) {
            $this->whatsapp_cloud_api = new WhatsAppCloudApi([
                'from_phone_number_id' => $this->phone_number_id,
                'access_token' => $this->token,
                'graph_version' => $this->version,
            ]);
        } else {
            $this->whatsapp_cloud_api = null;
        }

        $respuesta = generarSeccionSubirArchivo($this->imagen, $this->app_id, $this->version, $this->token, $this->url);

        if ($respuesta) {
            $this->whatsapp_cloud_api->updateBusinessProfile(['profile_picture_handle' =>$respuesta]);
            Log::info("Cargada la imagen: {$this->imagen}");
        } else {
            Log::error("Error al cargar la imagen: {$this->imagen}");
        }
    }
}
