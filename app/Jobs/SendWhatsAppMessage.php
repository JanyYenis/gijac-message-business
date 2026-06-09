<?php

namespace App\Jobs;

use App\Exceptions\ErrorException;
use App\Models\ConfiguracionMeta;
use App\Models\DetalleLink;
use App\Models\EnvioCampana;
use App\Models\VariableCampana;
use App\Models\EventoDetalle;
use App\Models\Mensaje;
use App\Models\Plantilla;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Netflie\WhatsAppCloudApi\Message\Template\Component;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;

class SendWhatsAppMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $contacto;
    protected $plantilla;
    protected $info;
    protected $idCampana;
    protected $userId;
    protected $nombres_variables;
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
    public function __construct($contacto, $plantilla, $info, $idCampana, $userId, $nombres_variables)
    {
        $this->contacto = $contacto;
        $this->plantilla = $plantilla;
        $this->info = $info;
        $this->idCampana = $idCampana;
        $this->userId = $userId;
        $this->nombres_variables = $nombres_variables;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $config = ConfiguracionMeta::where('estado', ConfiguracionMeta::ACTIVO)->where('uuid', $this->userId)->first();
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

        $errores = 0;
        $info['cod_campana'] = $this->idCampana;
        $info['telefono'] = $this->contacto->numero_completo;
        $info['cod_contacto'] = $this->contacto->id;
        $info['wamid'] = null;
        $info['estado'] = EnvioCampana::ACTIVO;

        // Aquí llamamos al servicio que envía el mensaje
        $response = $this->enviarCampana($this->contacto, $this->plantilla, $this->info, $this->idCampana, $this->nombres_variables);

        if (!$response || $response->isError()) {
            $errores++;
        } else {
            // Suponiendo que tienes el objeto de respuesta almacenado en la variable $response
            if ($response?->body()) {
                $data = json_decode($response?->body());
                $messages = $data->messages;
                $messageId = $messages[0]->id;
            }

            $info['wamid'] = $messageId ?? 'Nooo';

            $envio_campana = EnvioCampana::updateOrCreate([
                'cod_campana' => $info['cod_campana'],
                "telefono" => $info["telefono"]
            ], $info);

            if (!$envio_campana) {
                throw new ErrorException("Error al intentar registar el detalle de la campaña.");
            }

            $envio_campana->refresh();

            // Verifica el ID
            if (empty($envio_campana->id)) {
                throw new ErrorException("El envio de la campaña no tiene un ID asignado.");
            }

            $variablesDetalleUrls = VariableCampana::where('cod_campana', $info['cod_campana'])
                ->where('tipo', VariableCampana::URL)
                ->get();
            if (count($variablesDetalleUrls)) {
                foreach ($variablesDetalleUrls as $value) {
                    DetalleLink::updateOrCreate([
                        "cod_campana" => $info['cod_campana'],
                        "cod_detalle" => $envio_campana?->id,
                        "cod_contacto" => $this->contacto->id,
                        "cod_detalle_wpp" => $value->id,
                    ], [
                        "estado" => DetalleLink::ACTIVO
                    ]);
                }
            }

            $tipo = Mensaje::PLANTILLA;
            $estado = Mensaje::ENVIADO;
            $plantilla = Plantilla::with(
                'header',
                'body',
                'footer',
                'buttons',
            )->find($this->plantilla->id);
            $mensaje =  $plantilla?->body?->text ?? null;
            $variablesDetalle = VariableCampana::where('cod_campana', $this->idCampana)
                ->where('tipo', VariableCampana::TEXT)
                ->get();
            $valores = [];
            foreach ($variablesDetalle as $key => $value) {
                $llave = "{{".($this->nombres_variables[$key])."}}";
                $valores[$llave] = $value->valor;
                $campo = $value?->valor;
                if (isset($contacto->$campo)) {
                    $valores[$llave] = $contacto->$campo;
                }
            }

            $mensaje = str_replace(array_keys($valores), array_values($valores), $mensaje);
            $tipo_header = $plantilla?->header?->format ?? null;
            $header = null;
            if ($tipo_header && in_array($tipo_header, [Mensaje::IMAGEN, Mensaje::VIDEO, Mensaje::DOCUMENTO])) {
                $header = $this->info['file'] ?? null;
            } elseif ($tipo_header && $tipo_header == Mensaje::TEXTO) {
                $header = $plantilla?->header?->text ?? null;
            }
            $fooder = $plantilla?->footer?->text ?? null;
            $buttons = $plantilla?->buttons?->buttons ?? '';

            $idMensaje = $messageId ?? 'error';
            $mensajeEnviado = Mensaje::create([
                "campaign_id"   => $this->idCampana,
                "contact_id"    => $this->contacto->id,
                "wa_message_id" => $idMensaje ?? null,
                "wa_from"       => $this->phone_number_id,
                "wa_to"         => $this->contacto->numero_completo,
                "type"          => $tipo, // en tu caso: Mensaje::PLANTILLA
                "body"          => $mensaje, // el texto final con variables reemplazadas
                "metadata"      => (object) [
                    "tipo_header" => $tipo_header,
                    "header"      => $header,
                    "footer"      => $fooder,
                    "buttons"     => $buttons ?? '',
                    "variables"   => $valores ?? []
                ],
                "estado"        => $estado, // Mensaje::ENVIADO
                "sent_at"       => now(),
            ]);

            if (!$mensajeEnviado) {
                throw new ErrorException('Error al registrar el mensaje.');
            }
        }
    }

    public function enviarCampana($contacto, $plantilla, $info, $idCampana, $nombres_variables)
    {
        if ($this->whatsapp_cloud_api) {
            $telefono = $contacto?->numero_completo;
            $components = null;
            $component_header = [];
            $component_body = [];
            $component_buttons = [];
            if ($plantilla) {
                if (isset($plantilla->components) && count($plantilla->components)) {
                    // dd($plantilla->components);
                    foreach ($plantilla->components as $key => $componente) {
                        if ($componente->type == 'HEADER') {
                            if ($componente->format == 'TEXT') {
                                if (array_key_exists('header_text', $info)) {
                                    $component_header = [
                                        [
                                            'type' => 'text',
                                            'text' => $info['header_text']
                                        ]
                                    ];
                                }
                            } else if ($componente->format == 'DOCUMENT' || $componente->format == 'IMAGE' || $componente->format == 'VIDEO') {
                                if (array_key_exists('file', $info)) {
                                    if ($info['file']) {
                                        if ($componente->format == 'IMAGE') {
                                            $component_header = [
                                                [
                                                    'type' => 'image',
                                                    'image' => [
                                                        'link' => $info['file'],
                                                    ]
                                                ]
                                            ];
                                        } else if ($componente->format == 'VIDEO') {
                                            $component_header = [
                                                [
                                                    'type' => 'video',
                                                    'video' => [
                                                        'link' => $info['file'],
                                                    ]
                                                ]
                                            ];
                                        } else if ($componente->format == 'DOCUMENT') {
                                            $component_header = [
                                                [
                                                    'type' => 'document',
                                                    'document' => [
                                                        'link' => $info['file'],
                                                    ]
                                                ]
                                            ];
                                        }
                                    }
                                }
                            } else if ($componente->format == 'LOCATION') {
                                if (array_key_exists('latitude', $info) && array_key_exists('longitude', $info) && array_key_exists('name', $info) && array_key_exists('address', $info)) {
                                    $component_header = [
                                        [
                                            'latitude' => $info['latitude'],
                                            'longitude' => $info['longitude'],
                                            'name' => $info['name'],
                                            'address' => $info['address']
                                        ]
                                    ];
                                }
                            }
                        }
                        if ($componente->type == 'BODY') {
                            if (isset($componente->example) && isset($componente->example->body_text) && count($componente->example->body_text[0])) {
                                $variables = [];
                                foreach ($info['variables'] as $index => $variable) {
                                    if (property_exists($contacto, $variable)) {
                                        $variable = $contacto->$variable;
                                    }
                                    $variables[$index] = ['type' => 'text', 'text' => $variable];
                                }
                                $component_body = $variables;
                            } else if (isset($componente->example) && isset($componente->example->body_text_named_params) && count($componente->example->body_text_named_params)) {
                                $variables = [];
                                foreach ($info['variables'] as $index => $variable) {
                                    if (property_exists($contacto, $variable)) {
                                        $variable = $contacto->$variable;
                                    }
                                    $variables[$index] = [
                                        'type' => 'text',
                                        'parameter_name' => $nombres_variables[$index],
                                        'text' => $variable
                                    ];
                                }
                                $component_body = $variables;
                            }
                        }
                        if ($componente->type == 'BUTTONS') {
                            $component_buttons = [];
                            $botones = [];
                            foreach ($componente->buttons as $index => $boton) {
                                if ($boton?->type == 'URL') {
                                    if (isset($boton?->url)) {
                                        if (strpos($boton?->url, '{{1}}') !== false) {
                                            array_push($botones, [
                                                'type' => 'button',
                                                'sub_type' => 'url',
                                                'index' => $index,
                                                'parameters' => [
                                                    [
                                                        "type" => "payload",
                                                        "payload" => "campanas/redireccion/{$telefono}/{$idCampana}/{$index}/link"
                                                    ]
                                                ]
                                            ]);
                                        }
                                    }
                                }  else if ($boton?->type == 'FLOW') {
                                    array_push($botones, [
                                        'type' => 'button',
                                        'sub_type' => 'flow',
                                        'index' => $index,
                                        'parameters' => [
                                            [
                                                "type" => "action",
                                                "action" => []
                                            ]
                                        ]
                                    ]);
                                }
                            }
                            $component_buttons = $botones;
                        }
                    }
                    // dd($component_header, $component_body, $component_buttons);
                    $components = new Component($component_header, $component_body, $component_buttons);
                }

                if ($plantilla?->name && $plantilla?->language) {
                    $response = $this->whatsapp_cloud_api->sendTemplate($telefono, $plantilla->name, $plantilla->language, $components);
                    return $response;
                }
            } else {
                return false;
            }
        }
    }
}
