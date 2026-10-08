<?php

namespace App\Jobs;

use App\Models\WhatsappAccount;
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
    protected $empresaId;
    public $version;
    public $waba_id;
    public $phone_number_id	;
    public $token;
    public $numeroG;
    public $whatsapp_cloud_api;

    /**
     * Create a new job instance.
     */
    public function __construct($empresaId, $imagen, $url)
    {
        $this->empresaId = $empresaId;
        $this->imagen = $imagen;
        $this->url = $url;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));
        $config = WhatsappAccount::where('estado', WhatsappAccount::CONECTADO)->where('cod_empresa', $this->empresaId)->first();
        $this->version = $version ?? null;
        $this->waba_id = $config?->waba_id ?? null;
        $this->phone_number_id = $config?->phone_number_id ?? null;
        $this->token = $config?->access_token ?? null;
        $this->numeroG = $config?->phone_number ?? '573000000000';

        if ($this->phone_number_id && $this->token && $this->version) {
            $this->whatsapp_cloud_api = new WhatsAppCloudApi([
                'from_phone_number_id' => $this->phone_number_id,
                'access_token' => $this->token,
                'graph_version' => $this->version,
            ]);
        } else {
            $this->whatsapp_cloud_api = null;
        }

        $respuesta = generarSeccionSubirArchivo($this->imagen, $this->version, $this->token, $this->url);

        if ($respuesta) {
            $this->whatsapp_cloud_api->updateBusinessProfile(['profile_picture_handle' =>$respuesta]);
            Log::info("Cargada la imagen: {$this->imagen}");
        } else {
            Log::error("Error al cargar la imagen: {$this->imagen}");
        }
    }
}
