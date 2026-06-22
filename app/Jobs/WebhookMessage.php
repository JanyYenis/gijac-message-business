<?php

namespace App\Jobs;

use App\Events\MensajeSent;
use App\Models\Chatbots\ChatbotConnection;
use App\Models\Chatbots\ChatbotFlow;
use App\Models\Chatbots\ChatbotNode;
use App\Models\Chatbots\ChatbotNodeConfig;
use App\Models\Chatbots\ChatbotSession;
use App\Models\Chatbots\ChatbotSessionLog;
use App\Models\ClasificacionIa;
use App\Models\ConfiguracionMeta;
use App\Models\Contacto;
use App\Models\EnvioCampana;
use App\Models\Etiqueta;
use App\Models\EtiquetaContacto;
use App\Models\Mensaje;
use App\Models\Plan;
use App\Models\Usuario;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
        if (!$mensajeData) return;

        $tipo     = $mensajeData['type'] ?? 'text';
        $waFrom   = $mensajeData['from'] ?? '00000';
        $waTo     = $datos['metadata']['phone_number_id'] ?? null;
        $waMsgId  = $mensajeData['id'] ?? '--';
        $estado   = $datos['statuses'][0]['status'] ?? 'sent';
        $sentAt   = isset($mensajeData['timestamp']) ? Carbon::createFromTimestamp($mensajeData['timestamp']) : now();

        $estado = Mensaje::DATOS_ESTADO[$estado] ?? Mensaje::ENVIADO;
        $tipoMapped = Mensaje::DATOS_TIPO[$tipo] ?? ($tipo === 'interactive' ? null : Mensaje::TEXTO);

        $body = null; $header = null; $tipo_header = null; $valorChat = null; $idMedia = null;

        if ($tipoMapped === Mensaje::TEXTO) {
            $body = $mensajeData['text']['body'] ?? 'N/A';
        } elseif ($tipoMapped === Mensaje::IMAGEN) {
            $idMedia = $mensajeData['image']['id'] ?? null;
            $body = $mensajeData['image']['caption'] ?? null;
            $tipo_header = 'IMAGE';
            if ($idMedia) { $response = $whatsapp_cloud_api->downloadMedia($idMedia); file_put_contents(public_path("img/chat/{$idMedia}.jpg"), $response->body()); $header = asset("img/chat/{$idMedia}.jpg"); }
        } elseif ($tipoMapped === Mensaje::VIDEO) {
            $idMedia = $mensajeData['video']['id'] ?? null;
            $body = $mensajeData['video']['caption'] ?? null;
            $tipo_header = 'VIDEO';
            if ($idMedia) { $response = $whatsapp_cloud_api->downloadMedia($idMedia); file_put_contents(public_path("videos/chat/{$idMedia}.mp4"), $response->body()); $header = asset("videos/chat/{$idMedia}.mp4"); }
        } elseif ($tipoMapped === Mensaje::DOCUMENTO) {
            $idMedia = $mensajeData['document']['id'] ?? null;
            $body = $mensajeData['document']['caption'] ?? null;
            $nombreDoc = $mensajeData['document']['filename'] ?? ($idMedia . ".pdf");
            $tipo_header = 'DOCUMENT';
            if ($idMedia) { $response = $whatsapp_cloud_api->downloadMedia($idMedia); file_put_contents(public_path("documentos/chat/{$nombreDoc}"), $response->body()); $header = asset("documentos/chat/{$nombreDoc}"); }
        } elseif ($tipoMapped === Mensaje::AUDIO) {
            $idMedia = $mensajeData['audio']['id'] ?? null;
            $tipo_header = 'AUDIO';
            if ($idMedia) { $response = $whatsapp_cloud_api->downloadMedia($idMedia); file_put_contents(public_path("audios/chat/{$idMedia}.mp3"), $response->body()); $header = asset("audios/chat/{$idMedia}.mp3"); }
            $body = $header;
        } elseif ($tipo === 'interactive') {
            if (isset($mensajeData['interactive']['button_reply'])) {
                $tipoMapped = Mensaje::INTERACCION_BOTON;
                $body = $mensajeData['interactive']['button_reply']['title'] ?? null;
                $valorChat = $mensajeData['interactive']['button_reply']['id'] ?? null;
            } elseif (isset($mensajeData['interactive']['list_reply'])) {
                $tipoMapped = Mensaje::INTERACCION_LISTADO;
                $body = $mensajeData['interactive']['list_reply']['description'] ?? $mensajeData['interactive']['list_reply']['title'] ?? null;
                $valorChat = $mensajeData['interactive']['list_reply']['id'] ?? null;
            } elseif (isset($mensajeData['interactive']['nfm_reply'])) {
                $tipoMapped = Mensaje::FLOWS;
                $body = $mensajeData['interactive']['nfm_reply']['response_json'] ?? null;
            }
        } elseif ($tipoMapped === Mensaje::REACCION) {
            $body = $mensajeData['reaction']['emoji'] ?? null;
            $valorChat = $mensajeData['reaction']['message_id'] ?? null;
        } elseif ($tipoMapped === Mensaje::CONTACTO) {
            $body = json_encode($mensajeData['contacts'] ?? []);
        }

        $mensaje = Mensaje::updateOrCreate(['wa_message_id' => $waMsgId], [
            'wa_from' => $waFrom, 'wa_to' => $waTo, 'type' => $tipoMapped,
            'body' => $body, 'metadata' => $mensajeData, 'estado' => $estado, 'sent_at' => $sentAt,
        ]);

        if ($estado == Mensaje::LEIDO) {
            EnvioCampana::where('wamid', $waMsgId)->update(['apertura' => EnvioCampana::ABIERTO, 'fecha_apertura' => Carbon::now()]);
        }

        if ($estado == Mensaje::ENVIADO && $waFrom != $config->phone_number_id) {
            $contacto = Contacto::whereRaw("CONCAT(codigo_telefono, '', telefono) LIKE ?", ["%{$waFrom}%"])->where('uuid', $config->uuid)->first();
            $nuevo_mensaje = Mensaje::where('wa_message_id', $waMsgId)->first();
            if ($nuevo_mensaje && $contacto) { $nuevo_mensaje->nombre_completo = $contacto?->nombre_completo ?? null; }
            broadcast(new MensajeSent($nuevo_mensaje));
        }

        // --- NÚCLEO DEL CHATBOT NUEVO ---
        if ($estado == Mensaje::ENVIADO && $waFrom != $config->phone_number_id && $waTo == $config->phone_number_id && ($tipoMapped == Mensaje::TEXTO || $valorChat)) {
            $usuario = Usuario::where('uuid', $config->uuid)->first();
            if ($usuario) {
                $plan = Plan::find($usuario->cod_plan);

                // Clasificador IA (Mantenido igual)
                // $etiqueta_contacto = EtiquetaContacto::where('estado', EtiquetaContacto::ACTIVO)->where('cod_contacto', $contacto->id)->get();
                // if (!count($etiqueta_contacto)) { $this->clacificador($config->uuid, $body, $contacto->id); }

                // Chatbot Avanzado (NUEVA LÓGICA)
                if ($plan->tieneServicio('chatbots.avanzados') && $contacto?->estado_chatbot == Contacto::ACTIVO) {
                    Log::info("chatbot");
                    $this->processNewChatbot($config, $whatsapp_cloud_api, $waFrom, $contacto, $valorChat, $body);
                }
                // Chatbot IA básico (Mantenido igual)
                else if ($plan->tieneServicio('chatbots.ia') && $contacto?->estado_chatbot_ia == Contacto::ACTIVO) {
                    $audio = null;
                    $nombre_audio = null;
                    if ($tipo == 'audio') {
                        $audio = asset('audios/chat/'.$idMedia.'.mp3');
                        $nombre_audio = $idMedia.'.mp3';
                    }

                    $response = Http::withoutVerifying()->post(
                        'https://n8n.gijac.com/webhook/4e200b58-e8e8-4d9b-a975-ceb681ce0a68',
                        [
                            'numero'  => $waFrom,
                            'nombre'  => $contacto?->nombre_completo,
                            'mensaje' => $mensaje,
                        ]
                    );

                    if ($response->successful()) {
                        $data = $response->json();
                        $response_mensaje = $whatsapp_cloud_api->sendTextMessage($waFrom, $data['output']);

                        if ($response_mensaje?->body()) {
                            // Datos base del mensaje
                            $datos_chat = [
                                'campaign_id' => null,
                                'contact_id'  => $config->uuid,
                                'wa_message_id' => null,
                                'wa_from' => $config->phone_number_id,
                                'wa_to'   => $waFrom,
                                'type'    => Mensaje::TEXTO,
                                'body'    => $data['output'] ?? null,
                                'metadata'=> null,
                                'estado'  => Mensaje::ENVIADO,
                                'sent_at' => now(),
                            ];
                            $data = json_decode($response_mensaje?->body());
                            $messages = $data->messages ?? [];
                            $datos_chat['wa_message_id'] = $messages[0]->id ?? null;
                            $nuevo_mensaje = Mensaje::create($datos_chat);
                        }
                    }
                }
            }
        }
    }

       /**
     * NUEVO: Procesador principal del flujo
     */
    public function processNewChatbot($config, $whatsapp_cloud_api, $waFrom, $contacto, $valorChat, $textInput)
    {
        Log::info("=== INICIO PROCESO CHATBOT === Tel: {$waFrom}");

        // 1. Buscar el flujo activo del usuario
        $flow = ChatbotFlow::where('creado_por', $config->uuid)
            ->where('estado', ChatbotFlow::ACTIVO)
            ->first();

        if (!$flow) {
            Log::warning("CHATBOT: No se encontró flujo ACTIVO para el usuario UUID: {$config->uuid}");
            return;
        }
        Log::info("CHATBOT: Flujo encontrado ID: {$flow->id}");

        // 2. Buscar o crear sesión
        $session = ChatbotSession::where('flow_id', $flow->id)
            ->where('telefono_contacto', $waFrom)
            ->whereIn('estado', [ChatbotSession::ACTIVO, ChatbotSession::ESPERANDO])
            ->first();

        // Si no existe ninguna, crear una nueva
        if (!$session) {
            $session = ChatbotSession::create([
                'flow_id' => $flow->id,
                'telefono_contacto' => $waFrom,
                'estado' => ChatbotSession::ACTIVO,
                'flow_version' => $flow->versión_actual,
                'nombre_contacto' => $contacto?->nombre_completo,
                'fecha_inicio' => now(),
                'ultima_interacción_en' => now(),
            ]);
        }
        Log::info("CHATBOT: Sesión ID: {$session->id} | Nodo Actual DB: " . ($session->current_node_id ?? 'NULO'));

        $nextNode = null;

        // 3. Determinar el siguiente nodo
        if ($valorChat) {
            Log::info("CHATBOT: Rama INTERACTIVA (Botón/Lista). Valor recibido: {$valorChat}");
            $sourceOutput = 'output_' . $valorChat;

            $connection = ChatbotConnection::where('flow_id', $flow->id)
                ->where('source_node_id', $session->current_node_id)
                ->where('source_output', $sourceOutput)
                ->first();

            if ($connection) {
                $nextNode = ChatbotNode::find($connection->target_node_id);
                Log::info("CHATBOT: Conexión encontrada, siguiente nodo ID: {$nextNode->id}");
            } else {
                Log::warning("CHATBOT: NO se encontró conexión desde nodo {$session->current_node_id} por output {$sourceOutput}");
            }
        } else {
            Log::info("CHATBOT: Rama TEXTO LIBRE. Texto: '{$textInput}'");
            if (!$session->current_node_id) {
                Log::info("CHATBOT: Sesión NUEVA (no hay nodo actual). Buscando nodo START...");
                $startNode = $flow->nodoInicio;

                // COMPATIBILIDAD: Si no hay nodo START, buscar el que tenga principal = 1 (Texto antiguo)
                if (!$startNode) {
                    Log::warning("CHATBOT: No hay nodo START. Buscando nodo con principal = 1 (Método antiguo)...");
                    $startNode = ChatbotNode::where('flow_id', $flow->id)
                        ->where('principal', ChatbotNode::SI_PRINCIPAL)
                        ->first();
                }

                if ($startNode) {
                    Log::info("CHATBOT: Nodo de inicio encontrado ID: {$startNode->id} (Tipo: {$startNode->tipo})");
                    $triggerCfg = $startNode->config(ChatbotNodeConfig::KEY_TRIGGER);
                    $triggerValue = $triggerCfg ? $triggerCfg->valor : 'any';
                    Log::info("CHATBOT: Nodo START encontrado. Trigger configurado como: {$triggerValue}");

                    $shouldTrigger = false;
                    if ($triggerValue === 'any' || !$triggerCfg) {
                        $shouldTrigger = true;
                    } elseif ($triggerValue === 'keyword' && $keywordsCfg = $startNode->config(ChatbotNodeConfig::KEY_KEYWORDS)) {
                        $keywords = $keywordsCfg->valor;
                        if (in_array(strtolower($textInput), array_map('strtolower', $keywords))) {
                            $shouldTrigger = true;
                        }
                    }

                    if ($shouldTrigger) {
                        $nextNode = $startNode;
                        Log::info("CHATBOT: ¡Trigger activado! Se ejecutará el nodo START.");
                    } else {
                        Log::warning("CHATBOT: Trigger NO activado (keyword no coincide). No se hace nada.");
                    }
                } else {
                    Log::error("CHATBOT: El flujo NO tiene un nodo de tipo START.");
                }
            } else {
                Log::info("CHATBOT: Sesión EXISTENTE. Evaluar nodo actual: {$session->current_node_id}");
                $currentNode = ChatbotNode::find($session->current_node_id);

                if ($currentNode && in_array($currentNode->tipo, [ChatbotNode::CAPTURE, ChatbotNode::QUESTION])) {
                    $varNameCfg = $currentNode->config(ChatbotNodeConfig::KEY_VARIABLE);
                    if ($varNameCfg) {
                        $session->asignarVariable($varNameCfg->valor, $textInput);
                        Log::info("CHATBOT: Variable capturada: {$varNameCfg->valor} = {$textInput}");
                    }
                    $connection = ChatbotConnection::where('flow_id', $flow->id)
                        ->where('source_node_id', $currentNode->id)
                        ->where('source_output', 'output_1')
                        ->first();
                    if ($connection) $nextNode = ChatbotNode::find($connection->target_node_id);
                } else {
                    // NUEVA LÓGICA: Si envía texto libre en un nodo de Botones/Listas/End,
                    // cerramos esta sesión y REINICIAMOS el flujo desde cero.
                    Log::warning("CHATBOT: Texto libre en nodo interactivo. Reiniciando flujo...");

                    $session->update(['estado' => ChatbotSession::COMPLETADO, 'fecha_finalizado' => now()]);

                    // Buscar nodo inicio como si fuera la primera vez
                    $startNode = $flow->nodoInicio;
                    if (!$startNode) {
                        $startNode = ChatbotNode::where('flow_id', $flow->id)->where('principal', ChatbotNode::SI_PRINCIPAL)->first();
                    }

                    if ($startNode) {
                        $triggerCfg = $startNode->config(ChatbotNodeConfig::KEY_TRIGGER);
                        $triggerValue = $triggerCfg ? $triggerCfg->valor : 'any';
                        $shouldTrigger = ($triggerValue === 'any' || !$triggerCfg);

                        if ($shouldTrigger) {
                            $nextNode = $startNode; // Disparamos el inicio de nuevo
                        }
                    }
                }
            }
        }

        // 4. Ejecutar cadena de nodos
        if ($nextNode) {
            Log::info("CHATBOT: Lanzando executeNodeChain para nodo ID: {$nextNode->id}");
            $this->executeNodeChain($flow, $session, $nextNode, $waFrom, $config, $whatsapp_cloud_api);
        } else {
            Log::warning("CHATBOT: nextNode es NULO. No se ejecutará ninguna cadena.");
        }

        Log::info("=== FIN PROCESO CHATBOT ===");
    }

        /**
     * NUEVO: Ejecuta el nodo y revisa si el siguiente es auto_send
     */
    private function executeNodeChain(ChatbotFlow $flow, ChatbotSession $session, ChatbotNode $node, $waFrom, $config, $whatsapp_cloud_api)
    {
        Log::info("EJECUTANDO NODO: Tipo={$node->tipo}, ID={$node->id}");

        if ($node->tipo === ChatbotNode::END) {
            Log::info("EJECUTANDO NODO: Es un nodo END. Cerrando sesión.");
            $session->update(['estado' => ChatbotSession::COMPLETADO, 'fecha_finalizado' => now()]);
            $closeMsg = $node->config(ChatbotNodeConfig::KEY_CLOSE_MESSAGE);
            if ($closeMsg) {
                $whatsapp_cloud_api->sendTextMessage($waFrom, $closeMsg->valor);
            }
            return;
        }

        // Enviar mensaje del nodo actual
        $this->enviarMensajeChatbot($config, $whatsapp_cloud_api, $node, $waFrom, $session);

        // --- INICIO DIAGNÓSTICO AUTO_SEND ---
        Log::info("AUTO_SEND_DIAG: Buscando conexión desde {$node->id} por output_1...");

        // NUEVA REGLA DE ORO: Si el nodo que acabamos de enviar requiere interacción
        // (Botones o Lista), el flujo DEBE detenerse aquí para esperar al usuario.
        if (in_array($node->tipo, [ChatbotNode::BUTTONS, ChatbotNode::LIST])) {
            Log::info("EJECUTANDO NODO: El nodo actual es interactivo (Buttons/List). Deteniendo cadena para esperar respuesta.");
            $session->update([
                'current_node_id' => $node->id, // Guardamos ESTE nodo como el actual
                'estado' => ChatbotSession::ESPERANDO,
                'ultima_interacción_en' => now()
            ]);
            return; // Cortamos la recursividad del auto_send
        }

        $connection = ChatbotConnection::where('flow_id', $flow->id)
            ->where('source_node_id', $node->id)
            ->where('source_output', 'output_1')
            ->first();

        if (!$connection) {
            Log::warning("AUTO_SEND_DIAG: NO HAY CONEXIÓN en output_1. Fin de cadena.");
            return;
        }

        $nextNode = ChatbotNode::find($connection->target_node_id);
        Log::info("AUTO_SEND_DIAG: Conexión encontrada. Siguiente nodo ID: {$nextNode->id}");

        // Buscar el valor de auto_send en la BD
        Log::info("AUTO_SEND_DIAG: Buscando config KEY_AUTO_SEND (" . ChatbotNodeConfig::KEY_AUTO_SEND . ") en el siguiente nodo...");
        $autoSendConfig = $nextNode->config(ChatbotNodeConfig::KEY_AUTO_SEND);

        if (!$autoSendConfig) {
            Log::warning("AUTO_SEND_DIAG: ¡NO EXISTE la fila de configuración para auto_send en este nodo!");
        } else {
            $valorReal = $autoSendConfig->valor;
            Log::info("AUTO_SEND_DIAG: Valor encontrado en BD: " . json_encode($valorReal) . " (Tipo: " . gettype($valorReal) . ")");

            $isAutoSend = filter_var($valorReal, FILTER_VALIDATE_BOOLEAN);
            Log::info("AUTO_SEND_DIAG: ¿ filter_var dice que es TRUE? " . ($isAutoSend ? 'SÍ' : 'NO'));

            if ($isAutoSend) {
                Log::info("AUTO_SEND_DIAG: ¡¡ENTRANDO A RECURSIVIDAD PARA ENVIAR AUTO_SEND!!");
                $this->executeNodeChain($flow, $session, $nextNode, $waFrom, $config, $whatsapp_cloud_api);
            } else {
                Log::info("AUTO_SEND_DIAG: No es auto_send. Deteniendo cadena y actualizando sesión.");
                $session->update([
                    'current_node_id' => $nextNode->id,
                    'estado' => ChatbotSession::ESPERANDO,
                    'ultima_interacción_en' => now()
                ]);
            }
        }
        // --- FIN DIAGNÓSTICO AUTO_SEND ---
    }

    /**
     * NUEVO: Envía el mensaje vía WhatsApp leyendo los configs del nodo
     */
    public function enviarMensajeChatbot($config, $whatsapp_cloud_api, $node, $waFrom, $session = null)
    {
        Log::info("ENVIAR MENSAJE: Preparando envío para nodo ID {$node->id}");

        // Cargar todas las configs de este nodo de una vez
        $configs = $node->configs()->pluck('valor', 'key')->toArray();
        $getConfig = function($key) use ($configs) { return $configs[$key] ?? null; };

        $message = $getConfig(ChatbotNodeConfig::KEY_MESSAGE);
        $caption = $getConfig(ChatbotNodeConfig::KEY_CAPTION);
        $rawMediaUrl = $getConfig(ChatbotNodeConfig::KEY_URL);

        // COMPATIBILIDAD: Si el media_url se guardó como un array (lógica vieja),
        // extraemos solo la cadena de texto de la URL.
        $mediaUrl = is_array($rawMediaUrl) ? ($rawMediaUrl['url'] ?? null) : $rawMediaUrl;

        Log::info("ENVIAR MENSAJE: Message extraido: " . substr($message, 0, 50) . "...");
        Log::info("ENVIAR MENSAJE: MediaURL extraido: " . ($mediaUrl ?? 'NULO'));

        // Datos base...
        $datos = [
            'campaign_id' => null, 'contact_id' => $config->uuid, 'wa_message_id' => null,
            'wa_from' => $config->phone_number_id, 'wa_to' => $waFrom, 'type' => Mensaje::TEXTO,
            'body' => null, 'metadata' => null, 'estado' => Mensaje::ENVIADO, 'sent_at' => now(),
        ];
        $datos['body'] = $message;

        $response = null;

        try {
            // TEXT / START
            if (in_array($node->tipo, [ChatbotNode::TEXT, ChatbotNode::START, ChatbotNode::QUESTION])) {
                if ($message) {
                    Log::info("ENVIAR MENSAJE: Llamando a API WhatsApp -> sendTextMessage");
                    $response = $whatsapp_cloud_api->sendTextMessage($waFrom, $message);
                } else {
                    Log::warning("ENVIAR MENSAJE: El nodo es TEXTO pero no hay mensaje configurado.");
                }
            }
            // BUTTONS
            elseif ($node->tipo == ChatbotNode::BUTTONS) {
                Log::info("ENVIAR MENSAJE: Llamando a API WhatsApp -> sendButton");
                $buttons = $getConfig(ChatbotNodeConfig::KEY_BUTTONS) ?? [];
                $waButtons = []; $metaButtons = [];

                foreach ($buttons as $index => $btn) {
                    $btnId = (string)($index + 1);
                    $waButtons[] = new Button($btnId, $btn['label']);
                    $metaButtons[] = ['type' => 'text', 'text' => $btn['label']];
                }

                if (count($waButtons) > 0) {
                    $action = new ButtonAction($waButtons);
                    $response = $whatsapp_cloud_api->sendButton($waFrom, $message, $action);
                    $datos['type'] = Mensaje::INTERACCION_BOTON;
                    $datos['metadata'] = (object)["buttons" => json_encode($metaButtons)];
                }
            }
            // LIST
            elseif ($node->tipo == ChatbotNode::LIST) {
                Log::info("ENVIAR MENSAJE: Llamando a API WhatsApp -> sendList");
                $listTitle = $getConfig(ChatbotNodeConfig::KEY_LIST_TITLE) ?: 'Menú';
                $sectionName = $getConfig(ChatbotNodeConfig::KEY_SECTION_NAME) ?: 'Opciones';
                $rows = $getConfig(ChatbotNodeConfig::KEY_SECTIONS) ?? [];

                $waRows = []; $metaRows = [];
                foreach ($rows as $index => $row) {
                    $rowId = (string)($index + 1);
                    $waRows[] = new Row($rowId, $row['label']);
                    $metaRows[] = ['type' => 'text', 'text' => $row['label']];
                }

                if (count($waRows) > 0) {
                    $sections = [new Section($sectionName, $waRows)];
                    $action = new Action('Seleccionar', $sections);
                    $response = $whatsapp_cloud_api->sendList($waFrom, '✅ Menú', $message, $listTitle, $action);
                    $datos['type'] = Mensaje::INTERACCION_LISTADO;
                    $datos['metadata'] = (object)["buttons" => json_encode($metaRows)];
                }
            }
            // MEDIA (IMAGE, VIDEO, DOC, AUDIO)
            elseif (in_array($node->tipo, [ChatbotNode::IMAGE, ChatbotNode::VIDEO, ChatbotNode::DOC, ChatbotNode::AUDIO])) {
                Log::info("ENVIAR MENSAJE: Llamando a API WhatsApp -> sendMedia (Tipo: {$node->tipo})");
                if ($mediaUrl) {
                    $linkId = new LinkID($mediaUrl);
                    $filename = basename(parse_url($mediaUrl, PHP_URL_PATH));

                    if ($node->tipo == ChatbotNode::IMAGE) {
                        $response = $whatsapp_cloud_api->sendImage($waFrom, $linkId, $caption);
                        $datos['type'] = Mensaje::IMAGEN;
                    } elseif ($node->tipo == ChatbotNode::VIDEO) {
                        $response = $whatsapp_cloud_api->sendVideo($waFrom, $linkId, $caption);
                        $datos['type'] = Mensaje::VIDEO;
                    } elseif ($node->tipo == ChatbotNode::DOC) {
                        $response = $whatsapp_cloud_api->sendDocument($waFrom, $linkId, $filename, $caption ?: $message);
                        $datos['type'] = Mensaje::DOCUMENTO;
                    } elseif ($node->tipo == ChatbotNode::AUDIO) {
                        $response = $whatsapp_cloud_api->sendAudio($waFrom, $linkId);
                        $datos['type'] = Mensaje::AUDIO;
                    }
                    $datos['body'] = $caption ?? $filename;
                } else {
                    Log::error("ENVIAR MENSAJE: El nodo es multimedia pero NO tiene media_url configurada.");
                }
            } else {
                Log::warning("ENVIAR MENSAJE: Tipo de nodo no reconocido para envío directo: {$node->tipo}");
            }
        } catch (\Exception $e) {
            Log::error("ERROR CRÍTICO ENVIANDO WHATSAPP: " . $e->getMessage());
        }

        // Guardar en tabla de mensajes para UI
        if ($response?->body()) {
            $data = json_decode($response->body());
            $waMsgId = $data->messages[0]->id ?? null;
            $datos['wa_message_id'] = $waMsgId;
            Mensaje::create($datos);
            Log::info("ENVIAR MENSAJE: Mensaje guardado en tabla 'mensajes' con WA ID: {$waMsgId}");

            if ($session) {
                ChatbotSessionLog::create([
                    'session_id' => $session->id,
                    'node_id' => $node->id,
                    'direction' => ChatbotSessionLog::SALIDA,
                    'tipo_mensaje' => $this->mapNodeToLogType($node->tipo),
                    'contenido' => json_encode(['message' => $message, 'media_url' => $mediaUrl]),
                    'estado' => ChatbotSessionLog::ENVIADO,
                    'provider_message_id' => $waMsgId,
                    'fecha_envio' => now(),
                ]);
            }
        } else {
            Log::error("ENVIAR MENSAJE: La API de WhatsApp NO devolvió respuesta o falló silenciosamente.");
        }
    }

    private function mapNodeToLogType($nodeType) {
        $map = [
            ChatbotNode::TEXT => ChatbotSessionLog::TIPO_TEXT,
            ChatbotNode::START => ChatbotSessionLog::TIPO_TEXT,
            ChatbotNode::IMAGE => ChatbotSessionLog::TIPO_IMAGE,
            ChatbotNode::VIDEO => ChatbotSessionLog::TIPO_VIDEO,
            ChatbotNode::DOC => ChatbotSessionLog::TIPO_DOCUMENT,
            ChatbotNode::AUDIO => ChatbotSessionLog::TIPO_AUDIO,
            ChatbotNode::BUTTONS => ChatbotSessionLog::TIPO_BUTTONS,
            ChatbotNode::LIST => ChatbotSessionLog::TIPO_LIST,
        ];
        return $map[$nodeType] ?? ChatbotSessionLog::TIPO_TEXT;
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
