<?php

namespace App\Jobs;

use App\Exceptions\ErrorException;
use App\Models\WhatsappAccount;
use App\Models\DetalleErrorMensaje;
use App\Models\DetalleLink;
use App\Models\EnvioCampana;
use App\Models\VariableCampana;
use App\Models\Mensaje;
use App\Models\Plantilla;
use App\Models\Usuario;
use App\Notifications\WhatsappTokenNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Netflie\WhatsAppCloudApi\Message\Template\Component;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;

class SendWhatsAppMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $contacto;
    protected $plantilla;
    protected $info;
    protected $idCampana;
    protected $empresaId;
    protected $nombres_variables;
    public $version;
    public $waba_id;
    public $phone_number_id	;
    public $token;
    public $numeroG;
    public $whatsapp_cloud_api;

    // Sin límite de intentos: los reintentos por límite de velocidad de Meta se controlan con retryUntil().
    public $tries = 0;

    // Códigos de Meta que son temporales (límite de velocidad / servicio): se reintenta en vez de marcar fallo.
    const CODIGOS_TEMPORALES = [4, 1, 2, 80004, 80007, 130429, 131000, 131016, 131056];

    public function retryUntil()
    {
        return now()->addHours(6);
    }

    /**
     * Create a new job instance.
     */
    public function __construct($contacto, $plantilla, $info, $idCampana, $empresaId, $nombres_variables)
    {
        $this->contacto = $contacto;
        $this->plantilla = $plantilla;
        $this->info = $info;
        $this->idCampana = $idCampana;
        $this->empresaId = $empresaId;
        $this->nombres_variables = $nombres_variables;
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
        $this->token = $config?->access_token ? Crypt::decrypt($config->access_token) : null;
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

        $info['cod_campana'] = $this->idCampana;
        $info['telefono'] = $this->contacto->numero_completo;
        $info['cod_contacto'] = $this->contacto->id;
        $info['wamid'] = null;
        $info['estado'] = EnvioCampana::ACTIVO;

        if (!$this->whatsapp_cloud_api) {
            $this->registrarFallo($info, 0, 'Cuenta no conectada', 'La cuenta de WhatsApp no está conectada o no tiene token.');
            return;
        }

        // Límite de velocidad por número de WhatsApp (mensajes por segundo), compartido entre todos los workers
        $limite = (int) config('facebook.envio_por_segundo', env('WHATSAPP_ENVIO_POR_SEGUNDO', 10));
        $puede = Redis::throttle('wa-envio:' . $this->phone_number_id)
            ->allow(max(1, $limite))->every(1)->block(0)
            ->then(fn () => true, fn () => false);

        if (!$puede) {
            $this->release(rand(1, 3));
            return;
        }

        try {
            $response = $this->enviarCampana($this->contacto, $this->plantilla, $this->info, $this->idCampana, $this->nombres_variables);
        } catch (\Throwable $e) {
            // Error de red/conexión: se reintenta
            Log::warning('Error de conexión al enviar campaña', ['cod_campana' => $this->idCampana, 'error' => $e->getMessage()]);
            $this->release(min(300, 15 * $this->attempts()));
            return;
        }

        if (!$response || $response->isError()) {
            $cuerpo = $response ? ($response->decodedBody() ?? []) : [];
            $codigo = (int) ($cuerpo['error']['code'] ?? 0);
            $http = $response ? (int) $response->httpStatusCode() : 0;

            if ($response && ($http >= 500 || in_array($codigo, self::CODIGOS_TEMPORALES, true))) {
                Log::warning('Meta pidió reintentar el envío', ['cod_campana' => $this->idCampana, 'codigo' => $codigo, 'http' => $http]);
                $this->release(min(300, 15 * $this->attempts()));
                return;
            }

            Log::warning('Fallo al enviar campaña', [
                'cod_campana' => $this->idCampana,
                'telefono' => $info['telefono'],
                'detalle' => $cuerpo,
            ]);

            // Token revocado/vencido (190): se desconecta la cuenta y se avisa una sola vez al cliente
            if ($codigo === 190 && $config) {
                $cambio = WhatsappAccount::where('id', $config->id)
                    ->where('estado', WhatsappAccount::CONECTADO)
                    ->update(['estado' => WhatsappAccount::TOKEN_EXPIRADO]);
                if ($cambio) {
                    Usuario::where('uuid', $config->usuario_id)->first()?->notify(new WhatsappTokenNotification(null));
                }
            }
            $this->registrarFallo(
                $info,
                $codigo,
                $cuerpo['error']['type'] ?? 'Error de envío',
                $cuerpo['error']['message'] ?? 'No se pudo enviar el mensaje.',
                $cuerpo['error']['error_data']['details'] ?? null
            );
            return;
        }

        $data = json_decode($response->body() ?? '');
        $messageId = $data->messages[0]->id ?? null;
        $info['wamid'] = $messageId ?? 'Nooo';

        // El mensaje ya salió: si algo falla al registrarlo NO se relanza (evita enviarlo dos veces)
        try {
            $this->registrarEnvio($info, $messageId);
        } catch (\Throwable $e) {
            Log::error('Mensaje enviado pero no se pudo registrar', [
                'cod_campana' => $this->idCampana,
                'wamid' => $messageId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function registrarFallo(array $info, $codigo, $titulo, $mensaje, $detalle = null): void
    {
        $wamid = 'fallo-' . Str::uuid();
        $info['wamid'] = $wamid;

        EnvioCampana::updateOrCreate([
            'cod_campana' => $info['cod_campana'],
            'telefono' => $info['telefono'],
        ], $info);

        Mensaje::create([
            'campaign_id'   => $this->idCampana,
            'contact_id'    => $this->contacto->id,
            'wa_message_id' => $wamid,
            'wa_from'       => $this->phone_number_id,
            'wa_to'         => $this->contacto->numero_completo,
            'type'          => Mensaje::PLANTILLA,
            'body'          => '',
            'estado'        => Mensaje::FALLIDO,
            'sent_at'       => now(),
        ]);

        DetalleErrorMensaje::create([
            'wamid' => $wamid,
            'code' => $codigo,
            'title' => $titulo,
            'message' => $mensaje,
            'details' => $detalle,
        ]);
    }

    protected function registrarEnvio(array $info, $messageId): void
    {
        $envio_campana = EnvioCampana::updateOrCreate([
            'cod_campana' => $info['cod_campana'],
            'telefono' => $info['telefono'],
        ], $info);

        if (!$envio_campana) {
            throw new ErrorException(__("Error al intentar registar el detalle de la campaña."));
        }

        $envio_campana->refresh();

        if (empty($envio_campana->id)) {
            throw new ErrorException(__("El envio de la campaña no tiene un ID asignado."));
        }

        $variablesDetalleUrls = VariableCampana::where('cod_campana', $info['cod_campana'])
            ->where('tipo', VariableCampana::URL)
            ->get();

        foreach ($variablesDetalleUrls as $value) {
            DetalleLink::updateOrCreate([
                "cod_campana" => $info['cod_campana'],
                "cod_detalle" => $envio_campana->id,
                "cod_contacto" => $this->contacto->id,
                "cod_detalle_wpp" => $value->id,
            ], [
                "estado" => DetalleLink::ACTIVO
            ]);
        }

        $plantilla = Plantilla::with('header', 'body', 'footer', 'buttons')->find($this->plantilla->id);
        $mensaje = $plantilla?->body?->text ?? null;

        $variablesDetalle = VariableCampana::where('cod_campana', $this->idCampana)
            ->where('tipo', VariableCampana::TEXT)
            ->get();

        $valores = [];
        foreach ($variablesDetalle as $key => $value) {
            $llave = "{{".($this->nombres_variables[$key] ?? $key + 1)."}}";
            $valor = $value->valor;
            $campo = $value?->valor;

            // Bug corregido: era $contacto (indefinida), debe ser $this->contacto
            if ($campo && array_key_exists($campo, $this->contacto->getAttributes())) {
                $valor = $this->contacto->{$campo};
            }
            $valores[$llave] = $valor;
        }

        $mensaje = str_replace(array_keys($valores), array_values($valores), $mensaje);

        $tipo_header = $plantilla?->header?->format ?? null;
        $header = null;
        if ($tipo_header && in_array($tipo_header, [Mensaje::IMAGEN, Mensaje::VIDEO, Mensaje::DOCUMENTO])) {
            $header = $this->info['file'] ?? null;
        } elseif ($tipo_header == Mensaje::TEXTO) {
            $header = $plantilla?->header?->text ?? null;
        }

        $mensajeEnviado = Mensaje::create([
            "campaign_id"   => $this->idCampana,
            "contact_id"    => $this->contacto->id,
            "wa_message_id" => $messageId ?? 'error',
            "wa_from"       => $this->phone_number_id,
            "wa_to"         => $this->contacto->numero_completo,
            "type"          => Mensaje::PLANTILLA,
            "body"          => $mensaje,
            "metadata"      => (object) [
                "tipo_header" => $tipo_header,
                "header"      => $header,
                "footer"      => $plantilla?->footer?->text ?? null,
                "buttons"     => $plantilla?->buttons?->buttons ?? '',
                "variables"   => $valores,
            ],
            "estado"        => Mensaje::ENVIADO,
            "sent_at"       => now(),
        ]);

        if (!$mensajeEnviado) {
            throw new ErrorException(__('Error al registrar el mensaje.'));
        }
    }

    public function enviarCampana($contacto, $plantilla, $info, $idCampana, $nombres_variables)
    {
        if (!$this->whatsapp_cloud_api || !$plantilla) {
            return false;
        }

        $telefono = $contacto?->numero_completo;
        $component_header = [];
        $component_body = [];
        $component_buttons = [];

        if (isset($plantilla->components) && count($plantilla->components)) {
            foreach ($plantilla->components as $componente) {
                // ---------- HEADER ----------
                if ($componente->type == 'HEADER') {
                    $formato = $componente->format ?? null;

                    if ($formato == 'TEXT') {
                        // Solo enviar parámetro si el texto del header tiene variable
                        $tieneVariable = isset($componente->text) && strpos($componente->text, '{{') !== false;
                        if ($tieneVariable && array_key_exists('header_text', $info) && $info['header_text']) {
                            $component_header = [['type' => 'text', 'text' => $info['header_text']]];
                        }
                    } elseif (in_array($formato, ['DOCUMENT', 'IMAGE', 'VIDEO'])) {
                        if (!empty($info['file'])) {
                            $clave = strtolower($formato);
                            $component_header = [[
                                'type' => $clave,
                                $clave => ['link' => $info['file']],
                            ]];
                        }
                    } elseif ($formato == 'LOCATION') {
                        if (array_key_exists('latitude', $info) && array_key_exists('longitude', $info)
                            && array_key_exists('name', $info) && array_key_exists('address', $info)) {
                            $component_header = [[
                                'latitude' => $info['latitude'],
                                'longitude' => $info['longitude'],
                                'name' => $info['name'],
                                'address' => $info['address'],
                            ]];
                        }
                    }
                }

                // ---------- BODY ----------
                if ($componente->type == 'BODY') {
                    $esNamed = isset($componente->example->body_text_named_params)
                        && count($componente->example->body_text_named_params);

                    if ($esNamed) {
                        $variables = [];
                        foreach (($info['variables'] ?? []) as $index => $variable) {
                            if ($contacto && property_exists($contacto, $variable)) {
                                $variable = $contacto->$variable;
                            }
                            $variables[$index] = [
                                'type' => 'text',
                                'parameter_name' => $nombres_variables[$index] ?? null,
                                'text' => $variable,
                            ];
                        }
                        $component_body = $variables;
                    } elseif (isset($componente->example->body_text[0])) {
                        $variables = [];
                        foreach (($info['variables'] ?? []) as $index => $variable) {
                            if ($contacto && property_exists($contacto, $variable)) {
                                $variable = $contacto->$variable;
                            }
                            $variables[$index] = ['type' => 'text', 'text' => $variable];
                        }
                        $component_body = $variables;
                    }
                }

                // ---------- BUTTONS ----------
                if ($componente->type == 'BUTTONS') {
                    $botones = [];
                    foreach ($componente->buttons as $index => $boton) {
                        $btnTipo = $boton?->type;

                        if ($btnTipo == 'URL') {
                            $esDinamico = isset($boton->url) && strpos($boton->url, '{{1}}') !== false;
                            if ($esDinamico) {
                                $botones[] = [
                                    'type' => 'button',
                                    'sub_type' => 'url',
                                    'index' => $index,
                                    'parameters' => [[
                                        "type" => "payload",
                                        "payload" => "campanas/redireccion/{$telefono}/{$idCampana}/{$index}/link",
                                    ]],
                                ];
                            }
                            // URL estática (sin {{1}}): no se envían parámetros.
                        } elseif ($btnTipo == 'PHONE_NUMBER') {
                            // Botón de llamada, nunca lleva parámetros.
                            continue;
                        } elseif ($btnTipo == 'FLOW') {
                            $botones[] = [
                                'type' => 'button',
                                'sub_type' => 'flow',
                                'index' => $index,
                                'parameters' => [[
                                    "type" => "action",
                                    "action" => [],
                                ]],
                            ];
                        }
                    }
                    $component_buttons = $botones;
                }
            }

            $components = new Component($component_header, $component_body, $component_buttons);
        }

        if ($plantilla?->name && $plantilla?->language) {
            return $this->whatsapp_cloud_api->sendTemplate($telefono, $plantilla->name, $plantilla->language, $components ?? null);
        }

        return false;
    }
}
