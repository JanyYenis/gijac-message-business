<?php

namespace App\Jobs;

use App\Events\MensajeSent;
use App\Models\Chatbot;
use App\Models\ChatbotMessage;
use App\Models\ChatbotNode;
use App\Models\ChatbotOption;
use App\Models\ClasificacionIa;
use App\Models\ConfiguracionMeta;
use App\Models\ConfiguracionAi;
use App\Models\Contacto;
use App\Models\EnvioCampana;
use App\Models\Etiqueta;
use App\Models\EtiquetaContacto;
use App\Models\EventoDetalle;
use App\Models\Mensaje;
use App\Models\Plan;
use App\Models\PlanServicio;
use App\Models\Usuario;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\Button;
use Netflie\WhatsAppCloudApi\Message\ButtonReply\ButtonAction;
use Netflie\WhatsAppCloudApi\Message\Contact\ContactName;
use Netflie\WhatsAppCloudApi\Message\Contact\Phone;
use Netflie\WhatsAppCloudApi\Message\Contact\PhoneType;
use Netflie\WhatsAppCloudApi\Message\Media\LinkID;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Action;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Row;
use Netflie\WhatsAppCloudApi\Message\OptionsList\Section;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;

class WebhookMessage implements ShouldQueue
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
        $this->messages($this->datos, $this->app_id);
    }

    public function messages($datos, $app_id)
    {
        $config = ConfiguracionMeta::where('estado', ConfiguracionMeta::ACTIVO)->where('app_id', $app_id)->first();
        $whatsapp_cloud_api = new WhatsAppCloudApi([
            'from_phone_number_id' => $config->phone_number_id,
            'access_token' => $config->token,
            'graph_version' => $config->version,
        ]);
        $mensajeData = $datos['messages'][0] ?? null;

        if (!$mensajeData) {
            return response()->json(['error' => 'Sin mensajes'], 400);
        }

        $tipo     = $mensajeData['type'] ?? 'text';
        $waFrom   = $mensajeData['from'] ?? '00000'; // quién envía
        $waTo     = $datos['metadata']['phone_number_id'] ?? null; // a qué número de WA llegó
        $waMsgId  = $mensajeData['id'] ?? '--';
        $estado   = $datos['statuses'][0]['status'] ?? 'sent';
        $sentAt   = isset($mensajeData['timestamp']) ? Carbon::createFromTimestamp($mensajeData['timestamp']) : now();

        // Estado
        $estado = Mensaje::DATOS_ESTADO[$estado] ?? Mensaje::ENVIADO;

        // Tipo
        $tipoMapped = Mensaje::DATOS_TIPO[$tipo] ?? (
            $tipo === 'interactive' ? null : Mensaje::TEXTO
        );

        $body        = null;
        $header      = null;
        $tipo_header = null;
        $valorChat   = null;

        // TEXT
        if ($tipoMapped === Mensaje::TEXTO) {
            $body = $mensajeData['text']['body'] ?? 'N/A';
        }

        // IMAGEN
        elseif ($tipoMapped === Mensaje::IMAGEN) {
            $idMedia = $mensajeData['image']['id'] ?? null;
            $body = $mensajeData['image']['caption'] ?? null;
            $tipo_header = 'IMAGE';

            if ($idMedia) {
                $response = $whatsapp_cloud_api->downloadMedia($idMedia);
                file_put_contents(public_path("img/chat/{$idMedia}.jpg"), $response->body());
                $header = asset("img/chat/{$idMedia}.jpg");
            }
        }

        // VIDEO
        elseif ($tipoMapped === Mensaje::VIDEO) {
            $idMedia = $mensajeData['video']['id'] ?? null;
            $body = $mensajeData['video']['caption'] ?? null;
            $tipo_header = 'VIDEO';

            if ($idMedia) {
                $response = $whatsapp_cloud_api->downloadMedia($idMedia);
                file_put_contents(public_path("videos/chat/{$idMedia}.mp4"), $response->body());
                $header = asset("videos/chat/{$idMedia}.mp4");
            }
        }

        // DOCUMENTO
        elseif ($tipoMapped === Mensaje::DOCUMENTO) {
            $idMedia = $mensajeData['document']['id'] ?? null;
            $body = $mensajeData['document']['caption'] ?? null;
            $nombreDoc = $mensajeData['document']['filename'] ?? ($idMedia . ".pdf");
            $tipo_header = 'DOCUMENT';

            if ($idMedia) {
                $response = $whatsapp_cloud_api->downloadMedia($idMedia);
                file_put_contents(public_path("documentos/chat/{$nombreDoc}"), $response->body());
                $header = asset("documentos/chat/{$nombreDoc}");
            }
        }

        // AUDIO
        elseif ($tipoMapped === Mensaje::AUDIO) {
            $idMedia = $mensajeData['audio']['id'] ?? null;
            $tipo_header = 'AUDIO';

            if ($idMedia) {
                $response = $whatsapp_cloud_api->downloadMedia($idMedia);
                file_put_contents(public_path("audios/chat/{$idMedia}.mp3"), $response->body());
                $header = asset("audios/chat/{$idMedia}.mp3");
            }

            $body = $header; // para guardar el link
        }

        // INTERACTIVO
        elseif ($tipo === 'interactive') {
            if (isset($mensajeData['interactive']['button_reply'])) {
                $tipoMapped = Mensaje::INTERACCION_BOTON;
                $body = $mensajeData['interactive']['button_reply']['title'] ?? null;
                $valorChat = $mensajeData['interactive']['button_reply']['id'] ?? null;
            }
            elseif (isset($mensajeData['interactive']['list_reply'])) {
                $tipoMapped = Mensaje::INTERACCION_LISTADO;
                $body = $mensajeData['interactive']['list_reply']['description']
                    ?? $mensajeData['interactive']['list_reply']['title'] ?? null;
                $valorChat = $mensajeData['interactive']['list_reply']['id'] ?? null;
            }
            elseif (isset($mensajeData['interactive']['nfm_reply'])) {
                $tipoMapped = Mensaje::FLOWS;
                $body = $mensajeData['interactive']['nfm_reply']['response_json'] ?? null;
            }
        }

        // REACCIONES
        elseif ($tipoMapped === Mensaje::REACCION) {
            $body = $mensajeData['reaction']['emoji'] ?? null;
            $valorChat = $mensajeData['reaction']['message_id'] ?? null;
        }

        // CONTACTOS
        elseif ($tipoMapped === Mensaje::CONTACTO) {
            $body = json_encode($mensajeData['contacts'] ?? []);
        }

        // Guardar en BD
        $mensaje = Mensaje::updateOrCreate([
            'wa_message_id' => $waMsgId
        ], [
            'wa_from'   => $waFrom,
            'wa_to'     => $waTo,
            'type'      => $tipoMapped,
            'body'      => $body,
            'metadata'  => $mensajeData,
            'estado'    => $estado,
            'sent_at'   => $sentAt,
        ]);

        if ($estado == Mensaje::LEIDO) {
            EnvioCampana::where('wamid', $waMsgId)
                ->update(['apertura' => EnvioCampana::ABIERTO, 'fecha_apertura' => Carbon::now()]);
        }

        if ($estado == Mensaje::ENVIADO && $waFrom != $config->phone_number_id) {
            // Luego buscar el contacto
            $contacto = Contacto::whereRaw("CONCAT(codigo_telefono, '', telefono) LIKE ?", ["%{$waFrom}%"])
                ->where('uuid', $config->uuid)
                ->first();

            // Finalmente combinar los datos manualmente
            $nuevo_mensaje = Mensaje::where('wa_message_id', $waMsgId)->first();
            if ($nuevo_mensaje && $contacto) {
                $nuevo_mensaje->nombre_completo = $contacto?->nombre_completo ?? null;
            }
            broadcast(new MensajeSent($nuevo_mensaje));
        }

        if ($estado == Mensaje::ENVIADO && $waFrom != $config->phone_number_id &&
            $waTo == $config->phone_number_id && ($tipoMapped == Mensaje::TEXTO || $valorChat)) {
            $usuario = Usuario::where('uuid', $config->uuid)->first();
            if ($usuario) {
                $plan = Plan::find($usuario->cod_plan);
                $etiqueta_contacto = EtiquetaContacto::where('estado', EtiquetaContacto::ACTIVO)
                    ->where('cod_contacto', $contacto->id)
                    ->get();

                if (!count($etiqueta_contacto)) {
                    $this->clacificador($config->uuid, $body, $contacto->id);
                }

                if ($plan->tieneServicio('chatbots.avanzados') && $contacto?->estado_chatbot == Contacto::ACTIVO) {
                    $this->chatBot($config, $whatsapp_cloud_api, $waFrom, $mensaje, $valorChat);
                } else if ($plan->tieneServicio('chatbots.ia') && $contacto?->estado_chatbot_ia == Contacto::ACTIVO) {
                    $audio = null;
                    $nombre_audio = null;
                    if ($tipo == 'audio') {
                        $audio = asset('audios/chat/'.$idMedia.'.mp3');
                        $nombre_audio = $idMedia.'.mp3';
                    }

                    $response = Http::post(
                        'https://discord.com/channels/@me/987719605541810236/1514025931445108757',
                        [
                            'numero'  => $waFrom,
                            'nombre'  => $contacto?->nombre_completo,
                            'mensaje' => $mensaje,
                        ]
                    );

                    if ($response->successful()) {
                        $data = $response->json();
                        $response = $whatsapp_cloud_api->sendTextMessage($waFrom, $data['output']);
                    }
                }
            }
        }
    }

    public function chatbot($config, $whatsapp_cloud_api, $waFrom, $mensaje, $valorChat = null)
    {
        if (!$valorChat) {
            $bot = Chatbot::with('nodeInmediato.opcionesOrdenado', 'nodePrincipal.opcionesOrdenado')
                ->where('estado', Chatbot::ACTIVO)
                ->where('uuid', $config->uuid)
                ->first();

            if ($bot) {
                if ($bot->nodePrincipal) {
                    $principal = $bot->nodePrincipal;
                    $this->enviarMensajeChatbot($config, $whatsapp_cloud_api, $principal, $waFrom);
                }

                if (count($bot->nodeInmediato)) {
                    foreach ($bot->nodeInmediato as $node) {
                        $this->enviarMensajeChatbot($config, $whatsapp_cloud_api, $node, $waFrom);
                    }
                }
            }
        } else {
            $opcion = ChatbotOption::with('node')->find($valorChat);
            if ($opcion->next_node_id) {
                $node = ChatbotNode::where('number', $opcion->next_node_id)
                    ->where('chatbot_id', $opcion->node->chatbot_id)
                    ->first();

                $this->enviarMensajeChatbot($config, $whatsapp_cloud_api, $node, $waFrom);
            }
        }
    }

    public function enviarMensajeChatbot($config, $whatsapp_cloud_api, $node, $waFrom)
    {
        // Datos base del mensaje
        $datos = [
            'campaign_id' => null,
            'contact_id'  => $config->uuid,
            'wa_message_id' => null,
            'wa_from' => $config->phone_number_id,
            'wa_to'   => $waFrom,
            'type'    => Mensaje::TEXTO,
            'body'    => null,
            'metadata'=> null,
            'estado'  => Mensaje::ENVIADO,
            'sent_at' => now(),
        ];
        $datos['body'] = $node->message;
        if ($node->type == ChatbotNode::TEXTO) {
            $response = $whatsapp_cloud_api->sendTextMessage($waFrom, $node->message);
        } else if ($node->type == ChatbotNode::BOTON) {
            $datos['type'] = Mensaje::INTERACCION_BOTON;
            $rows = [];
            $buttons = [];
            foreach ($node->opcionesOrdenado as $opcion) {
                $rows[] = new Button($opcion->id, $opcion->label);
                $buttons[] = ['type' => 'text', 'text' => $opcion->label];
            }
            $action = new ButtonAction($rows);

            $response = $whatsapp_cloud_api->sendButton(
                $waFrom,
                $node->message,
                $action,
            );

            $datos['metadata'] = (object) [
                "tipo_header" => null,
                "header"      => null,
                "footer"      => null,
                "buttons"     => json_encode($buttons),
                "variables"   => []
            ];
        } else if ($node->type == ChatbotNode::LISTA) {
            $datos['type'] = Mensaje::INTERACCION_LISTADO;
            $rows = [];
            $buttons = [];
            foreach ($node->opcionesOrdenado as $opcion) {
                $rows[] = new Row($opcion->id, $opcion->label);
                $buttons[] = ['type' => 'text', 'text' => $opcion->label];
            }
            $sections = [new Section('Stars', $rows)];
            $action = new Action('Enviar', $sections);

            $response = $whatsapp_cloud_api->sendList(
                $waFrom,
                '✅ Lista',
                $node->message,
                'GIJAC WEB',
                $action
            );

            $datos['metadata'] = (object) [
                "tipo_header" => null,
                "header"      => null,
                "footer"      => null,
                "buttons"     => json_encode($buttons),
                "variables"   => []
            ];
        } else if ($node->type == ChatbotNode::DOCUMENTO) {
            if ($node->media_url) {
                $document_link = $node->media_url;
                $document_name = basename($node->media_url);
                $link_id = new LinkID($document_link);
                $response = $whatsapp_cloud_api->sendDocument($waFrom, $link_id, $document_name, $node->message);
            }
        }

        if ($response?->body()) {
            $data = json_decode($response?->body());
            $messages = $data->messages ?? [];
            $datos['wa_message_id'] = $messages[0]->id ?? null;
        }

        $mensaje = Mensaje::create($datos);
    }

    public function clacificador($uuid, $mensaje, $contacto)
    {
        $clasificacion = ClasificacionIa::where('estado', ClasificacionIa::ACTIVO)
            ->where('cod_usuario', $uuid)
            ->first();

        try {
            // Llamar a la API de etiquetado
            $response = Http::timeout(30)->post('http://127.0.0.1:8001/etiquetar', [
                'mensaje' => $mensaje,
                'prompt_usuario' => $clasificacion?->prompt ? $clasificacion?->prompt : "Clasificación de Sentimientos: Analiza el sentimiento del mensaje y clasifícalo como 'positivo', 'negativo' o 'neutral'. Considera el tono, las palabras utilizadas y el contexto general.",
                'model_name' => 'gpt-oss:120b-cloud',
                'temperature' => 0.3,
            ]);

            // Verificar si la respuesta fue exitosa
            if ($response->successful()) {
                $data = $response->json();

                $etiqueta = Etiqueta::updateOrCreate([
                    'slug' => Str::slug($data['etiqueta'], '.')
                ], [
                    'nombre' => $data['etiqueta'],
                    'uuid' => $uuid,
                    'estado' => Etiqueta::ACTIVO
                ]);

                $etiqueta->refresh();

                // Verifica el ID
                if (empty($etiqueta->id)) {
                    throw new ErrorException("La etiqueta no tiene un ID asignado.");
                }

                EtiquetaContacto::updateOrCreate([
                    "cod_contacto" => $contacto,
                    "cod_etiqueta" => $etiqueta->id,
                ], [
                    "estado" => EtiquetaContacto::ACTIVO
                ]);

                return response()->json([
                    'success' => true,
                    'etiqueta' => $data['etiqueta'],
                    'mensaje_original' => $data['mensaje_original']
                ]);
            }

            // Manejar errores de la API
            return response()->json([
                'success' => false,
                'error' => 'Error al etiquetar el mensaje',
                'details' => $response->json()
            ], $response->status());

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error de conexión con la API',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
