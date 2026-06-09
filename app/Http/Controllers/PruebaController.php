<?php

namespace App\Http\Controllers;

use App\Models\ChatPregunta;
use App\Models\ConfiguracionMeta;
use App\Models\ConfiguracionAi;
use App\Models\Contacto;
use App\Models\Mensaje;
use App\Models\Plan;
use App\Models\Usuario;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\Button;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\ButtonAction;
use Netflie\WhatsAppCloudApi\Message\Contact\ContactName;
use Netflie\WhatsAppCloudApi\Message\Contact\Phone;
use Netflie\WhatsAppCloudApi\Message\Contact\PhoneType;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Action;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Row;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Section;
use Netflie\WhatsAppCloudApi\Message\Template\Component;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;

class PruebaController extends Controller
{
    public function index()
    {
        $this->chatbot('189706530645476', '573152094191', 'hola', null, null, null,);
    }

    public function chatBot($app_id, $telefono, $mensaje, $valor = null, $audio = null, $nombre_audio = null)
    {
        $config = ConfiguracionMeta::where('estado', ConfiguracionMeta::ACTIVO)->where('app_id', $app_id)->first();
        $whatsapp_cloud_api = new WhatsAppCloudApi([
            'from_phone_number_id' => $config->phone_number_id,
            'access_token' => $config->token,
            'graph_version' => $config->version,
        ]);

        $usuario = Usuario::find($config->cod_usuario);
        if ($usuario && $usuario->cod_plan) {
            $plan = Plan::where('id', $usuario->cod_plan)->whereHas('chatbotsAi')->exists();
            if ($plan) {
                $configuracion_ai = ConfiguracionAi::firstWhere([
                    'cod_usuario' => $config->cod_usuario,
                    'estado' => ConfiguracionAi::ACTIVO
                ]) ?? null;

                // $contacto = Contacto::whereRaw("CONCAT(codigo_tel, telefono) = ?", [$telefono])->first() ?? null;

                if ($configuracion_ai) {
                    $datosChat['session_id'] = $config->phone_number_id;
                    $datosChat['phone_number'] = $telefono;
                    if ($audio && $nombre_audio) {
                        $datosChat['nombre_audio'] = $nombre_audio;
                        $datosChat['audio_url'] = $audio;
                    } else {
                        $datosChat['question'] = $mensaje;
                    }
                    $datosChat['system_prompt'] = $configuracion_ai?->prompt;

                    $response = Http::timeout(60)->withHeaders([
                        'Content-Type' => 'application/json',
                    ])->post('http://agente-meta.gijac.com:8000/chat', $datosChat);

                    // Manejar la respuesta
                    if ($response->successful()) {
                        $mensajeResponse = json_decode($response->body(), true); // Muestra la respuesta
                        $respuestaWhasapp = $whatsapp_cloud_api->sendTextMessage($telefono, $mensajeResponse['response']);

                        if ($respuestaWhasapp?->body()) {
                            $data = json_decode($respuestaWhasapp?->body());
                            $messages = $data->messages;
                            $messageId = $messages[0]->id;
                        }

                        $idMensaje = $messageId ?? 'Nooo';
                        $tipo = 'text';
                        $para = $telefono;
                        $de = $config->phone_number_id;
                        $agente = 1;
                        $estado = 'sent';

                        $mensajeEnviado = Mensaje::create([
                            "id_mensaje" => $idMensaje,
                            "tipo" => $tipo,
                            "de" => $de,
                            "para" => $para,
                            "mensaje" => $mensajeResponse['response'],
                            "estado" => $estado,
                            "fecha_envio" => Carbon::now(),
                            "id_agente" => $agente,
                        ]);
                    } else {
                        echo 'Error: ' . $response->status(); // Muestra el código de estado si falla
                    }
                }
            } else {
                $plan = Plan::where('id', $usuario->cod_plan)->whereHas('chatbot')->exists();
                if ($plan) {
                    // Obtener el último mensaje enviado
                    $ultimoMensaje = Mensaje::where('tipo', Mensaje::CHAT_BOT)
                        ->where('para', $telefono)
                        ->orderByDesc('fecha_envio')
                        ->orderByDesc('id') // Desempate usando el ID
                        ->first();

                    // El último mensaje fue enviado hace más de una hora
                    $header = null;
                    $fooder = null;
                    $tipo_header = null;
                    $tipo = Mensaje::CHAT_BOT;

                    if ($ultimoMensaje) {
                        // Verificar si el último mensaje fue enviado hace más de una hora
                        $diferenciaEnMinutos = Carbon::now()->diffInMinutes(Carbon::parse($ultimoMensaje->fecha_envio));

                        // Comprueba si la diferencia en minutos es mayor que 15
                        if ($diferenciaEnMinutos > 15) {
                            if ($valor) {
                                $respuesta = explode('_', $valor);
                                $siguientePregunta = ChatPregunta::with('respuestasActivas')->where('respuesta_id', $respuesta[2])
                                    ->where('estado', ChatPregunta::ACTIVO)
                                    ->get();

                                if (count($siguientePregunta)) {
                                    foreach ($siguientePregunta as $siguiente) {
                                        $valoresRespuesta = [];
                                        if ($siguiente?->tipo == ChatPregunta::BOTON || $siguiente?->tipo == ChatPregunta::LISTA) {
                                            $valoresRespuesta = $siguiente->respuestasActivas;
                                        }
                                        $respuestas = count($siguiente?->respuestasActivas) ? $siguiente?->respuestasActivas : [];
                                        $resultado = $this->enviarRespuestaChat($app_id, $telefono, $siguiente, $respuestas, $tipo, $header, $tipo_header);
                                    }
                                }
                            } else {
                                // $datosCache = [];
                                // if (Cache::get($telefono)) {
                                //     $datosCache = Cache::get($telefono);
                                // }
                                // if ($datosCache) {
                                //     $datosCache = json_decode($datosCache, true);
                                // }
                                $ultimaPregunta = ChatPregunta::where('descripcion', $ultimoMensaje->mensaje)->first();
                                // $datosCache[$ultimaPregunta->nombre_dato] = $mensaje;
                                // Cache::put($telefono, json_encode($datosCache));
                                $siguientePrincipal = ChatPregunta::with('respuestasActivas')
                                    ->whereIn('id', explode(',', $ultimaPregunta->pregunta_siguiente_id))
                                    ->where('estado', ChatPregunta::ACTIVO)
                                    ->get();

                                if (count($siguientePrincipal)) {
                                    foreach ($siguientePrincipal as $siguiente) {
                                        if ($siguiente && $siguiente?->tipo) {
                                            $valoresRespuesta = [];
                                            if ($siguiente?->tipo == ChatPregunta::BOTON || $siguiente?->tipo == ChatPregunta::LISTA) {
                                                $valoresRespuesta = $siguiente->respuestasActivas;
                                            }
                                            $this->enviarRespuestaChat($app_id, $telefono, $siguiente, $valoresRespuesta, $tipo, $header, $tipo_header);
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($valor) {
                                $respuesta = explode('_', $valor);
                                $siguientePregunta = ChatPregunta::with('respuestasActivas')->where('respuesta_id', $respuesta[2])
                                    ->where('estado', ChatPregunta::ACTIVO)
                                    ->get();

                                if (count($siguientePregunta)) {
                                    foreach ($siguientePregunta as $siguiente) {
                                        $valoresRespuesta = [];
                                        if ($siguiente?->tipo == ChatPregunta::BOTON || $siguiente?->tipo == ChatPregunta::LISTA) {
                                            $valoresRespuesta = $siguiente->respuestasActivas;
                                        }
                                        $respuestas = count($siguiente?->respuestasActivas) ? $siguiente?->respuestasActivas : [];
                                        $resultado = $this->enviarRespuestaChat($app_id, $telefono, $siguiente, $respuestas, $tipo, $header, $tipo_header);
                                    }
                                }
                            } else {
                                $preguntaPrincipal = ChatPregunta::with('respuestasActivas')->where('principal', ChatPregunta::PRINCIPAL)
                                    ->where('estado', ChatPregunta::ACTIVO)
                                    ->first();

                                if ($preguntaPrincipal && $preguntaPrincipal?->tipo) {
                                    $valoresRespuesta = [];
                                    if ($preguntaPrincipal?->tipo == ChatPregunta::BOTON || $preguntaPrincipal?->tipo == ChatPregunta::LISTA) {
                                        $valoresRespuesta = $preguntaPrincipal->respuestasActivas;
                                    }
                                    $this->enviarRespuestaChat($app_id, $telefono, $preguntaPrincipal, $valoresRespuesta, $tipo, $header, $tipo_header);
                                }
                            }
                        }
                    } else {
                        $preguntaPrincipal = ChatPregunta::with('respuestasActivas')->where('principal', ChatPregunta::PRINCIPAL)
                            ->where('estado', ChatPregunta::ACTIVO)
                            ->first();

                        if ($preguntaPrincipal && $preguntaPrincipal?->tipo) {
                            $valoresRespuesta = [];
                            if ($preguntaPrincipal?->tipo == ChatPregunta::BOTON || $preguntaPrincipal?->tipo == ChatPregunta::LISTA) {
                                $valoresRespuesta = $preguntaPrincipal->respuestasActivas;
                            }
                            $this->enviarRespuestaChat($app_id, $telefono, $preguntaPrincipal, $valoresRespuesta, $tipo, $header, $tipo_header);
                        }
                    }
                }
            }
        }
    }

    public function enviarRespuestaChat($app_id, $telefono, $pregunta, $respuestasActivas = [], $tipo, $header, $tipo_header)
    {
        $config = ConfiguracionMeta::where('estado', ConfiguracionMeta::ACTIVO)->where('app_id', $app_id)->first();
        $whatsapp_cloud_api = new WhatsAppCloudApi([
            'from_phone_number_id' => $config->phone_number_id,
            'access_token' => $config->token,
            'graph_version' => $config->version,
        ]);

        if ($pregunta->tipo == ChatPregunta::BOTON) {
            $rows = [];

            foreach ($respuestasActivas as $key => $value) {
                $rows[] = new Button($value->valor, $value->descripcion);
            }

            $action = new ButtonAction($rows);

            $response = $whatsapp_cloud_api->sendButton(
                $telefono,
                $pregunta?->descripcion ?? null,
                $action,
                'Gibot', // Optional: Specify a header (type "text")
                'GIJAC WEB' // Optional: Specify a footer
            );
        } else if ($pregunta->tipo == ChatPregunta::LISTA) {
            $rows = [];

            foreach ($respuestasActivas as $key => $value) {
                $rows[] = new Row($value->valor, $value->descripcion);
            }

            $sections = [new Section('Stars', $rows)];
            $action = new Action('Submit', $sections);

            $response = $whatsapp_cloud_api->sendList(
                $telefono,
                'Gibot',
                $pregunta?->descripcion ?? null,
                'GIJAC WEB',
                $action
            );
        } elseif ($pregunta->tipo == ChatPregunta::TEXTO) {
            $response = $whatsapp_cloud_api->sendTextMessage($telefono, $pregunta?->descripcion);
        } elseif ($pregunta->tipo == ChatPregunta::CONTACTO) {
            $name = new ContactName('Linea de', 'Emergencias');
            $phone = new Phone('123', PhoneType::CELL());

            $response = $whatsapp_cloud_api->sendContact($telefono, $name, $phone);
        }

        $messageId = null;
        if ($response?->body()) {
            $data = json_decode($response?->body());
            $messages = $data->messages;
            $messageId = $messages[0]->id;
        }

        $mensaje = Mensaje::create([
            "id_mensaje" => $messageId,
            "tipo" => $tipo,
            "de" => $config->phone_number_id,
            "para" => $telefono,
            "mensaje" => $pregunta?->descripcion ?? null,
            "header" => $header,
            "tipo_header" => $tipo_header,
            "estado" => 'sent',
            "fecha_envio" => Carbon::now(),
        ]);
    }
}
