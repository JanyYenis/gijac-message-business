<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Jobs\SendWhatsAppMessage;
use App\Models\Campana;
use App\Models\ConfiguracionMeta;
use App\Models\Contacto;
use App\Models\Mensaje;
use App\Models\Plan;
use App\Models\Plantilla;
use App\Models\Usuario;
use Illuminate\Http\Request;
use libphonenumber\PhoneNumberUtil;
use Netflie\WhatsAppCloudApi\Message\Template\Component;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;

class GeneralController extends Controller
{
    public function campanas(Request $request)
    {
        $fecha_inicio = $request->input('fecha_inicio') ?? null;
        $fecha_fin = $request->input('fecha_fin') ?? null;

        if (!$fecha_fin || !$fecha_inicio) {
            return response()->json([
                'estado' => 400,
                'mensaje' => 'La fecha de inicio y la fecha de finalizacion son requeridas.'
            ]);
        }

        $campanas = Campana::with('infoTipo', 'infoCategoria', 'etiqueta', 'plantilla')
            ->whereBetween('fecha_envio', [$fecha_inicio, $fecha_fin])
            ->whereNotIn('estado', [Campana::CANCELADO, Campana::ELIMINADO])
            ->where('uuid', $request->input('user_id'))
            ->get();

        return response()->json([
            'campanas' => $campanas,
            'estado' => 200
        ]);
    }

    public function campana(Request $request)
    {
        $id = $request->input('id') ?? null;

        if (!$id) {
            return response()->json([
                'estado' => 400,
                'mensaje' => 'El campo id es requerido.'
            ]);
        }

        $campana = Campana::with('infoTipo', 'infoCategoria', 'etiqueta', 'plantilla')
            ->where('id', $id)
            ->whereNotIn('estado', [Campana::CANCELADO, Campana::ELIMINADO])
            ->where('uuid', $request->input('user_id'))
            ->first();

        return response()->json([
            'campana' => $campana,
            'estado' => 200
        ]);
    }

    public function detalleCampanas(Request $request) {}

    public function enviarMensaje(Request $request)
    {
        $plantilla = $request->input('plantilla') ?? null;
        $telefono = $request->input('telefono') ?? null;

        $errores = [];
        if (!$plantilla) {
            $errores['plantilla'] = "El campo de plantilla es requerido.";
        }
        if (!$telefono) {
            $errores['telefono'] = "El campo de telefono es requerido.";
        }

        if (count($errores)) {
            return response()->json([
                'estado' => 400,
                'mensaje' => $errores,
            ]);
        }

        // dispatch(new SendWhatsAppMessage($envio_c?->contacto, $plantilla, $variables, $campana->id, $campana->cod_empresa, $nombres_variables));
    }

    public function crearCampana(Request $request)
    {
        $nombre = $request->input('nombre');
        $descripcion = $request->input('descripcion');
        $categoria = $request->input('categoria');
        $fecha_envio = $request->input('fecha_envio');
        $plantilla = $request->input('plantilla');
    }

    public function enviarCampana(Request $request)
    {
        $campana = $request->input('campana');
        $plantilla = $request->input('plantilla') ?? null;
        $telefono = $request->input('telefono') ?? null;

        $errores = [];
        if (!$plantilla) {
            $errores['plantilla'] = "El campo de plantilla es requerido.";
        }
        if (!$telefono) {
            $errores['telefono'] = "El campo de telefono es requerido.";
        }

        if (count($errores)) {
            return response()->json([
                'estado' => 400,
                'mensaje' => $errores,
            ]);
        }

        // dispatch(new SendWhatsAppMessage($envio_c?->contacto, $plantilla, $variables, $campana->id, $campana->cod_empresa, $nombres_variables));
    }

    public function enviarPlantillaIndividual(Request $request)
    {
        $request->validate([
            'plantilla' => 'required',
            'telefono'  => 'required|string',
            'user_id'   => 'required|string',
            'info'      => 'array',
        ]);

        $idPlantilla = $request->input('plantilla');
        $telefono    = $request->input('telefono');
        $info        = $request->input('info', []);
        $nombres_variables        = $request->input('nombres_variables', []);
        $variables   = $info['variables'] ?? [];

        if (!str_starts_with($telefono, '+')) {
            $telefono = '+' . $telefono;
        }

        $usuario = Usuario::where('uuid', $request->input('user_id'))->first();
        $config = ConfiguracionMeta::where('estado', ConfiguracionMeta::ACTIVO)
            ->where('cod_empresa', $usuario?->empresa?->id)
            ->first();

        if (!$config || !$config->token || !$config->version) {
            return response()->json(['estado' => 'error', 'mensaje' => 'No hay configuración de Meta activa para este usuario.'], 422);
        }

        // Traemos la plantilla directo de Meta (igual que en store()/update())
        $url = "https://graph.facebook.com/{$config->version}/{$idPlantilla}";
        $respuesta = consultaBase($url, 'GET', $config->token);
        $plantilla = json_decode($respuesta);

        if (!$plantilla || isset($plantilla->error)) {
            return response()->json([
                'estado' => 'error',
                'mensaje' => 'No se pudo obtener la plantilla desde Meta.',
                'detalle' => $plantilla->error ?? null,
            ], 404);
        }

        $contacto = Contacto::whereRaw("CONCAT(codigo_telefono, '', telefono) LIKE ?", ["%{$telefono}%"])
            ->where(function($query) use($request, $usuario) {
                $query->where('uuid', $request->input('user_id'))
                    ->orWhere('cod_empresa', $usuario->empresa?->id);
            })
            ->first() ?? null;

        if (!$contacto) {
            $empresa = $usuario?->empresa ?? false;
            $tienePlan = $empresa?->facturaVigente?->cod_plan ?? null;
            $esDemo = $usuario?->demo ?? null;
            $cantidadContactosActivos = Contacto::where('estado', Contacto::ACTIVO)
                ->where('cod_empresa', $empresa->id)
                ->count();
            if ($tienePlan) {
                $plan = Plan::find($tienePlan);
                if ($plan?->max_contactos) {
                    if ($plan?->max_contactos <= $cantidadContactosActivos) {
                        return response()->json(['estado' => 'error', 'mensaje' => 'Has superado el limite de contactos activos para tu plan.'], 500);
                    }
                }
            } else if ($esDemo) {
                if (30 <= $cantidadContactosActivos) {
                    return response()->json(['estado' => 'error', 'mensaje' => 'Has superado el limite de 30 contactos activos para tu plan demo.'], 500);
                }
            } else {
                return response()->json(['estado' => 'error', 'mensaje' => 'Por favor selecciona uno de nuestros planes para crear un contacto.'], 500);
            }

            $phoneUtil = PhoneNumberUtil::getInstance();
            $parsedNumber = $phoneUtil->parse($telefono, null);
                // Obtener el código del país
            $countryCode = $parsedNumber->getCountryCode();

            // Obtener el resto del número sin el código del país
            $nationalNumber = $parsedNumber->getNationalNumber();

            $contacto = Contacto::create([
                'telefono' => $nationalNumber,
                'codigo_telefono' => $countryCode,
                'uuid' => $usuario->uuid,
                'cod_empresa' => $empresa?->id,
            ]);
        }

        $whatsapp_cloud_api = new WhatsAppCloudApi([
            'from_phone_number_id' => $config->phone_number_id,
            'access_token' => $config->token,
            'graph_version' => $config->version,
        ]);

        $component_header  = [];
        $component_body    = [];
        $component_buttons = [];

        foreach ($plantilla->components as $componente) {
            $tipo = $componente->type ?? null;

            // ---------- HEADER ----------
            if ($tipo === 'HEADER') {
                $formato = $componente->format ?? null;

                if ($formato === 'TEXT') {
                    // Solo necesita parámetro si el texto del header tiene variable {{1}}
                    if (isset($componente->text) && strpos($componente->text, '{{') !== false) {
                        if (!empty($info['header_text'])) {
                            $component_header = [['type' => 'text', 'text' => $info['header_text']]];
                        }
                    }
                } elseif (in_array($formato, ['IMAGE', 'VIDEO', 'DOCUMENT'])) {
                    if (!empty($info['file'])) {
                        $clave = strtolower($formato);
                        $component_header = [[
                            'type' => $clave,
                            $clave => ['link' => $info['file']],
                        ]];
                    }
                } elseif ($formato === 'LOCATION') {
                    if (isset($info['latitude'], $info['longitude'], $info['name'], $info['address'])) {
                        $component_header = [[
                            'latitude'  => $info['latitude'],
                            'longitude' => $info['longitude'],
                            'name'      => $info['name'],
                            'address'   => $info['address'],
                        ]];
                    }
                }
            }

            // ---------- BODY ----------
            if ($tipo === 'BODY') {
                $parametros = [];

                if (($plantilla->parameter_format ?? 'POSITIONAL') === 'NAMED') {
                    $named = $componente->example->body_text_named_params ?? [];
                    foreach ($named as $param) {
                        $nombre = $param->param_name;
                        $valor  = $info[$nombre] ?? $param->example;
                        if ($contacto && property_exists($contacto, $valor)) {
                            $valor = $contacto->$valor;
                        }
                        $parametros[] = [
                            'type' => 'text',
                            'parameter_name' => $nombre,
                            'text' => $valor,
                        ];
                    }
                } else {
                    $cantidad = isset($componente->example->body_text[0])
                        ? count($componente->example->body_text[0])
                        : count($variables);

                    for ($i = 0; $i < $cantidad; $i++) {
                        $valor = $variables[$i] ?? '';
                        if ($contacto && property_exists($contacto, $valor)) {
                            $valor = $contacto->$valor;
                        }
                        $parametros[] = ['type' => 'text', 'text' => $valor];
                    }
                }

                $component_body = $parametros;
            }

            // ---------- BUTTONS ----------
            if ($tipo === 'BUTTONS') {
                $botones = [];
                foreach ($componente->buttons as $index => $boton) {
                    $btnTipo = $boton->type ?? null;

                    if ($btnTipo === 'URL') {
                        $esDinamico = isset($boton->url) && strpos($boton->url, '{{1}}') !== false;
                        if ($esDinamico) {
                            // En "Copiar código" (autenticación) o URL dinámica normal,
                            // usamos la primera variable como valor del placeholder.
                            $valor = $variables[0] ?? '';
                            $botones[] = [
                                'type' => 'button',
                                'sub_type' => 'url',
                                'index' => $index,
                                'parameters' => [
                                    ['type' => 'text', 'text' => $valor],
                                ],
                            ];
                        }
                        // Si la URL no tiene {{1}}, es estática: no se envían parámetros.
                    } elseif ($btnTipo === 'PHONE_NUMBER') {
                        // Botón de llamada estático, nunca lleva parámetros.
                        continue;
                    } elseif ($btnTipo === 'FLOW') {
                        $botones[] = [
                            'type' => 'button',
                            'sub_type' => 'flow',
                            'index' => $index,
                            'parameters' => [
                                ['type' => 'action', 'action' => []],
                            ],
                        ];
                    }
                }
                $component_buttons = $botones;
            }
        }

        if (empty($plantilla->name) || empty($plantilla->language)) {
            return response()->json(['estado' => 'error', 'mensaje' => 'La plantilla no tiene nombre o idioma.'], 422);
        }

        $components = new Component($component_header, $component_body, $component_buttons);

        try {
            $resultado = $whatsapp_cloud_api->sendTemplate($telefono, $plantilla->name, $plantilla->language, $components);
        } catch (\Throwable $e) {
            return response()->json(['estado' => 'error', 'mensaje' => 'Error al enviar: '.$e->getMessage()], 500);
        }

        if (!$resultado || $resultado->isError()) {
            return response()->json([
                'estado' => 'error',
                'mensaje' => 'WhatsApp rechazó el envío.',
                'detalle' => $resultado?->decodedBody(),
            ], 422);
        }

        $data  = json_decode($resultado->body());
        $wamid = $data->messages[0]->id ?? null;

        $valores = [];
        foreach ($variables as $key => $value) {
            $llave = "{{".($nombres_variables[$key] ?? $key + 1)."}}";
            $valor = $value;
            $campo = $value;

            // Bug corregido: era $contacto (indefinida), debe ser $this->contacto
            if ($campo && property_exists($contacto, $campo)) {
                $valor = $contacto->$campo;
            }
            $valores[$llave] = $valor;
        }

        $plantilla = Plantilla::find($idPlantilla);
        $mensaje = $plantilla?->body?->text ?? null;
        $mensaje = str_replace(array_keys($valores), array_values($valores), $mensaje);

        $tipo_header = $plantilla?->header?->format ?? null;
        $header = null;
        if ($tipo_header && in_array($tipo_header, [Mensaje::IMAGEN, Mensaje::VIDEO, Mensaje::DOCUMENTO])) {
            $header = $info['file'] ?? null;
        } elseif ($tipo_header == Mensaje::TEXTO) {
            $header = $plantilla?->header?->text ?? null;
        }

        $mensajeEnviado = Mensaje::create([
            "contact_id"    => $contacto->id,
            "wa_message_id" => $wamid ?? 'error',
            "wa_from"       => $config->phone_number_id,
            "wa_to"         => $contacto->numero_completo,
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
            return response()->json([
                'estado' => 'error',
                'mensaje' => 'Error al registrar el mensaje.'
                ], 422);
        }

        return response()->json([
            'estado' => 'success',
            'mensaje' => 'Plantilla enviada correctamente.',
            'wamid' => $wamid,
        ]);
    }
}
