<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionMeta;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $cod_config;
    public $version;
    public $waba_id;
    public $app_id;
    public $phone_number_id	;
    public $token;
    public $numeroG;
    public $whatsapp_cloud_api;
    public $demo;
    public $plan;
    public $uuid;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            setlocale(LC_TIME, 'es_ES.utf8');
            Carbon::setLocale('es');
            if (Auth::check()) { // Verifica si el usuario está autenticado
                $this->uuid = Auth::user()->empresa?->id ?? null;
                $config = ConfiguracionMeta::where('estado', ConfiguracionMeta::ACTIVO)
                    ->where('cod_empresa', $this->uuid)
                    ->first();
                $this->demo = Auth::user()->demo;
                $this->plan = Auth::user()->cod_plan;
                $this->version = $config?->version ?? null;
                $this->waba_id = $config?->waba_id ?? null;
                $this->app_id = $config?->app_id ?? null;
                $this->phone_number_id = $config?->phone_number_id ?? null;
                $this->token = $config?->token ?? null;
                $this->numeroG = $config?->numero ?? '573000000000';
                $this->cod_config = $config?->id;

                if ($this->phone_number_id && $this->token && $this->version) {
                    $this->whatsapp_cloud_api = new WhatsAppCloudApi([
                        'from_phone_number_id' => $this->phone_number_id,
                        'access_token' => $this->token,
                        'graph_version' => $this->version,
                    ]);
                } else {
                    $this->whatsapp_cloud_api = null;
                }
            } else {
                // Maneja el caso cuando no hay un usuario autenticado
                $this->version = null;
                $this->waba_id = null;
                $this->app_id = null;
                $this->phone_number_id = null;
                $this->token = null;
                $this->numeroG = '573000000000';
                $this->whatsapp_cloud_api = null;
            }

            return $next($request);
        });
    }
}
