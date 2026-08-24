<?php

namespace App\Jobs;

use App\DTO\ParsedWhatsAppMessage;
use App\Models\AutomatizacionN8n;
use App\Models\Chatbots\ChatbotAiAssistant;
use App\Models\Chatbots\ChatbotAiHistorial;
use App\Models\Chatbots\ChatbotFlow;
use App\Models\ConfiguracionMeta;
use App\Models\Contacto;
use App\Models\Empresa;
use App\Models\Mensaje;
use App\Models\Plan;
use App\Services\AI\AudioTranscriptionService;
use App\Services\AI\ChatbotAiPromptBuilder;
use App\Services\AI\OllamaService;
use App\Services\Chatbot\ChatbotNodeExecutor;
use App\Services\Chatbot\ChatbotSessionResolver;
use App\Services\Mensajes\MessageContentService;
use App\Services\N8n\N8nService;
use App\Services\WhatsApp\IncomingMessageParser;
use App\Services\WhatsApp\MessageStore;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Throwable;

class WebhookMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $datos;
    protected $app_id;

    public function __construct($datos, $app_id)
    {
        $this->datos = $datos;
        $this->app_id = $app_id;
    }

    public function handle(): void
    {
        $this->messages($this->datos, $this->app_id);
    }

    /**
     * Punto de entrada: parsea el payload, persiste el mensaje
     * y decide si debe entrar al chatbot avanzado o al IA básico.
     */
    public function messages($datos, $app_id)
    {
        $config = ConfiguracionMeta::where('estado', ConfiguracionMeta::ACTIVO)
            ->where('app_id', $app_id)
            ->first();

        if (!$config) {
            return;
        }

        $api = new WhatsAppCloudApi([
            'from_phone_number_id' => $config->phone_number_id,
            'access_token' => $config->token,
            'graph_version' => $config->version,
        ]);

        $mensajeData = $datos['messages'][0] ?? null;
        if (!$mensajeData) return;

        // --- 1. Datos base del webhook ---
        $tipoOriginal = $mensajeData['type'] ?? 'text';
        $waFrom  = $mensajeData['from'] ?? '00000';
        $waTo    = $datos['metadata']['phone_number_id'] ?? null;
        $waMsgId = $mensajeData['id'] ?? '--';
        $estadoRaw = $datos['statuses'][0]['status'] ?? 'sent';
        $sentAt  = isset($mensajeData['timestamp'])
            ? Carbon::createFromTimestamp($mensajeData['timestamp'])
            : now();

        $estado = Mensaje::DATOS_ESTADO[$estadoRaw] ?? Mensaje::ENVIADO;
        $tipoMapped = Mensaje::DATOS_TIPO[$tipoOriginal] ?? ($tipoOriginal === 'interactive' ? null : Mensaje::TEXTO);

        // --- 2. Parsear el contenido del mensaje según su tipo ---
        $parser = app(IncomingMessageParser::class);
        $parsed = $parser->parse($mensajeData, $tipoMapped, $tipoOriginal, $api);

        // --- 3. Resolver el contacto (si aplica) ---
        $contacto = null;
        if ($estado == Mensaje::ENVIADO && $waFrom != $config->phone_number_id) {
            $contacto = Contacto::where('numero_completo', $waFrom)
                ->where('cod_empresa', $config->cod_empresa)
                ->first();
        }

        // --- 4. Guardar el mensaje entrante ---
        $store = app(MessageStore::class);
        $store->guardarEntrante($mensajeData, $parsed, $waFrom, $waTo, $waMsgId, $estado, $sentAt, $contacto?->id);

        // --- 5. Marcar apertura de campaña si es "leído" ---
        if ($estado == Mensaje::LEIDO) {
            $store->marcarAperturaCampana($waMsgId);
        }

        // --- 6. Broadcast a la UI si es un mensaje entrante nuevo ---
        if ($estado == Mensaje::ENVIADO && $waFrom != $config->phone_number_id) {
            $store->difundir($waMsgId, $contacto);
        }

        // --- 7. Enrutar al chatbot correspondiente ---
        $esEntradaValidaParaChatbot = $estado == Mensaje::ENVIADO
            && $waFrom != $config->phone_number_id
            && $waTo == $config->phone_number_id
            && ($tipoMapped == Mensaje::TEXTO || $parsed->valorChat);

        if (!$esEntradaValidaParaChatbot) return;

        $empresa = Empresa::find($config->cod_empresa);
        if (!$empresa) return;

        $plan = Plan::find($empresa->facturaVigente?->cod_plan);
        if (!$plan) return;

        // if ($plan->tieneServicio('chatbots.avanzados') && $contacto?->estado_chatbot == Contacto::ACTIVO && $empresa?->nodoActivo()) {
        //     $this->runChatbotAvanzado($config, $api, $waFrom, $contacto, $parsed);
        // } else
        if ($plan->tieneServicio('chatbots.ia') && $contacto?->estado_chatbot_ia == Contacto::ACTIVO && $empresa?->asistenteActivo()) {
            $asistente = $empresa->asistenteActivo;
            $this->runChatbotIaBasico($config, $api, $waFrom, $contacto, $asistente, $waMsgId);
        // } elseif ($plan->tieneServicio('chatbot.n8n') && $contacto?->estado_chatbot_ia == Contacto::ACTIVO && $empresa?->automatizacionN8nActiva()) {
        //     $this->runChatbotWebhook($config, $api, $waFrom, $contacto, $tipoOriginal, $parsed, $waMsgId);
        }
    }

    /**
     * Chatbot avanzado (flujo visual con nodos).
     */
    private function runChatbotAvanzado(ConfiguracionMeta $config, WhatsAppCloudApi $api, string $waFrom, Contacto $contacto, ParsedWhatsAppMessage $parsed): void
    {
        $flow = ChatbotFlow::where('cod_empresa', $config->cod_empresa)
            ->where('estado', ChatbotFlow::ACTIVO)
            ->first();

        if (!$flow) {
            return;
        }

        $resolver = app(ChatbotSessionResolver::class);
        $session = $resolver->resolveSession($flow, $waFrom, $contacto->nombre_completo);
        $nextNode = $resolver->resolveNextNode($flow, $session, $parsed->valorChat, $parsed->body);

        if (!$nextNode) {
            Log::warning("CHATBOT: nextNode nulo para sesión {$session->id}");
            return;
        }

        app(ChatbotNodeExecutor::class)->execute($flow, $session, $nextNode, $waFrom, $config, $api);
    }

    /**
     * Chatbot IA básico (webhook a n8n).
     */
    private function runChatbotWebhook(ConfiguracionMeta $config, WhatsAppCloudApi $api, string $waFrom, Contacto $contacto, string $tipoOriginal, ParsedWhatsAppMessage $parsed, string $waMsgId): void
    {
        $mensaje = Mensaje::where(
            'wa_message_id',
            $waMsgId
        )->first();

        if (!$mensaje) {
            return;
        }

        $texto = app(MessageContentService::class)
            ->obtenerTexto($mensaje);

        if (!$texto) {
            return;
        }

        $automatizacion = AutomatizacionN8n::query()
            ->where('cod_empresa', $config->cod_empresa)
            ->where('estado', AutomatizacionN8n::ACTIVO)
            ->where('webhook_activo', AutomatizacionN8n::ACTIVO)
            ->first();

        if (!$automatizacion) {
            return;
        }

        $payload = [
            "event" => "message.received",
            "automation_id" => $automatizacion->id,
            "company_id" => $config->cod_empresa,
            "contact" => [
                "id" => $contacto->id,
                "name" => $contacto->nombre_completo,
                "phone" => $contacto->nombre_completo
            ],
            "message" => [
                "id" => $waMsgId,
                "type" => "text",
                "text" => $texto,
                "timestamp" => now()
            ],
            "channel" => [
                "type" => "whatsapp",
                "phone_number_id" => $config->phone_number_id,
            ]
        ];

        $data = app(N8nService::class)
            ->ejecutar($automatizacion, $payload, $contacto);

        if (!$data || empty($data['output'])) {
            return;
        }
        $responseMensaje = $api->sendTextMessage($waFrom, $data['output']);

        if (!$responseMensaje?->body()) return;

        $body = json_decode($responseMensaje->body());

        Mensaje::create([
            'campaign_id' => null,
            'contact_id' => $contacto->id,
            'wa_message_id' => $body->messages[0]->id ?? null,
            'wa_from' => $config->phone_number_id,
            'wa_to' => $waFrom,
            'type' => Mensaje::TEXTO,
            'body' => $data['output'] ?? null,
            'metadata' => null,
            'estado' => Mensaje::ENVIADO,
            'sent_at' => now(),
            'response_type' => Mensaje::N8N,
        ]);
    }

    /**
     * Chatbot IA (asistente Ollama configurado en el módulo de Asistente IA).
     */
    private function runChatbotIaBasico(ConfiguracionMeta $config, WhatsAppCloudApi $api, string $waFrom, Contacto $contacto, ChatbotAiAssistant $asistente, string $waMsgId): void
    {
        $capacidades = $asistente->capacidades ?? [];

        if (!in_array(ChatbotAiAssistant::RESPONDER_AUTOMATICAMENTE, $capacidades)) {
            return; // el asistente existe pero tiene apagada la respuesta automática
        }

        $mensaje = Mensaje::where('wa_message_id', $waMsgId)->first();
        if (!$mensaje) return;

        $texto = app(MessageContentService::class)->obtenerTexto($mensaje);
        if (!$texto) return;

        // --- Horario ---
        if ($asistente->respetar_horario && !$this->dentroDeHorarioAsistente($asistente)) {
            if ($asistente->mensaje_fuera_horario) {
                $this->enviarYRegistrarRespuestaIa($api, $config, $waFrom, $contacto, $asistente->mensaje_fuera_horario, Mensaje::IA);
            }
            return;
        }

        // --- Palabras clave -> transferencia a humano ---
        if (in_array(ChatbotAiAssistant::TRANSFERIR_A_AGENTE_HUMANO, $capacidades) && !empty($asistente->palabras_clave)) {
            $textoLower = mb_strtolower($texto);
            $coincide = collect($asistente->palabras_clave)
                ->contains(fn($p) => str_contains($textoLower, mb_strtolower(trim($p))));

            if ($coincide) {
                if ($asistente->mensaje_transferencia) {
                    $this->enviarYRegistrarRespuestaIa($api, $config, $waFrom, $contacto, $asistente->mensaje_transferencia, Mensaje::HUMANA);
                }
                $contacto->update(['estado_chatbot_ia' => Contacto::INACTIVO]);
                return;
            }
        }

        // --- Historial / contexto ---
        $guardarContexto      = in_array(ChatbotAiAssistant::GUARDAR_CONTEXTO, $capacidades);
        $recordarConversacion = in_array(ChatbotAiAssistant::REMEMBRAR_CONVERSACION, $capacidades);

        $historial = ($recordarConversacion && $guardarContexto)
            ? $this->obtenerHistorialAsistente($contacto)
            : [];

        $systemPrompt = app(ChatbotAiPromptBuilder::class)->construir($asistente);

        try {
            $respuesta = app(OllamaService::class)->chat(
                $systemPrompt,
                array_merge($historial, [['role' => 'user', 'content' => $texto]]),
                $asistente->modelo,
                ['temperature' => $asistente->creatividad / 100]
            );
        } catch (Throwable $e) {
            report($e);
            return;
        }

        if (!$respuesta) return;

        $this->enviarYRegistrarRespuestaIa($api, $config, $waFrom, $contacto, $respuesta, Mensaje::IA);

        if ($guardarContexto) {
            $this->guardarHistorialAsistente($contacto, $config, $texto, $respuesta);
        }
    }

    private function dentroDeHorarioAsistente(ChatbotAiAssistant $asistente): bool
    {
        if (!$asistente->hora_inicio || !$asistente->hora_fin) {
            return true;
        }

        $ahora = now()->format('H:i:s');
        return $ahora >= $asistente->hora_inicio && $ahora <= $asistente->hora_fin;
    }

    private function obtenerHistorialAsistente(Contacto $contacto): array
    {
        $registro = ChatbotAiHistorial::where('contacto_id', $contacto->id)->first();
        if (!$registro) return [];

        // Ventana de contexto: últimos 20 mensajes (10 turnos) para no saturar el prompt
        return collect($registro->historial ?? [])->take(-20)->values()->all();
    }

    private function guardarHistorialAsistente(Contacto $contacto, ConfiguracionMeta $config, string $textoUsuario, string $respuestaAsistente): void
    {
        $registro = ChatbotAiHistorial::firstOrNew(['contacto_id' => $contacto->id]);
        $registro->cod_empresa = $config->cod_empresa;
        $registro->telefono = $contacto->numero_completo;

        $historial   = $registro->historial ?? [];
        $historial[] = ['role' => 'user', 'content' => $textoUsuario];
        $historial[] = ['role' => 'assistant', 'content' => $respuestaAsistente];

        $registro->historial = array_slice($historial, -40); // hasta 20 turnos
        $registro->save();
    }

    private function enviarYRegistrarRespuestaIa(WhatsAppCloudApi $api, ConfiguracionMeta $config, string $waFrom, Contacto $contacto, string $texto, int $responseType): void
    {
        $responseMensaje = $api->sendTextMessage($waFrom, $texto);
        if (!$responseMensaje?->body()) return;

        $body = json_decode($responseMensaje->body());

        Mensaje::create([
            'campaign_id'    => null,
            'contact_id'     => $contacto->id,
            'wa_message_id'  => $body->messages[0]->id ?? null,
            'wa_from'        => $config->phone_number_id,
            'wa_to'          => $waFrom,
            'type'           => Mensaje::TEXTO,
            'body'           => $texto,
            'metadata'       => null,
            'estado'         => Mensaje::ENVIADO,
            'sent_at'        => now(),
            'response_type'  => $responseType,
        ]);
    }
}
