<?php

namespace App\Jobs;

use App\DTO\ParsedWhatsAppMessage;
use App\Models\Chatbots\ChatbotFlow;
use App\Models\ConfiguracionMeta;
use App\Models\Contacto;
use App\Models\Empresa;
use App\Models\Mensaje;
use App\Models\Plan;
use App\Services\Chatbot\ChatbotNodeExecutor;
use App\Services\Chatbot\ChatbotSessionResolver;
use App\Services\WhatsApp\IncomingMessageParser;
use App\Services\WhatsApp\MessageStore;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;

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
            Log::warning("WEBHOOK: No hay ConfiguracionMeta activa para app_id {$app_id}");
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
        Log::info('Telefono: '.$waFrom);
        Log::info('Cod Empresa: '.$config?->cod_empresa);
        Log::info('Contacto: '.$contacto);

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

        if ($plan->tieneServicio('chatbots.avanzados') && $contacto?->estado_chatbot == Contacto::ACTIVO) {
            $this->runChatbotAvanzado($config, $api, $waFrom, $contacto, $parsed);
        } elseif ($plan->tieneServicio('chatbots.ia') && $contacto?->estado_chatbot_ia == Contacto::ACTIVO) {
            $this->runChatbotIaBasico($config, $api, $waFrom, $contacto, $tipoOriginal, $parsed);
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
            Log::warning("CHATBOT: No hay flujo ACTIVO para cod_empresa {$config->cod_empresa}");
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
    private function runChatbotIaBasico(ConfiguracionMeta $config, WhatsAppCloudApi $api, string $waFrom, Contacto $contacto, string $tipoOriginal, ParsedWhatsAppMessage $parsed): void
    {
        $mensaje = Mensaje::where('wa_from', $waFrom)->latest('id')->first();

        $response = Http::withoutVerifying()->post(
            'https://n8n.gijac.com/webhook/4e200b58-e8e8-4d9b-a975-ceb681ce0a68',
            [
                'numero'  => $waFrom,
                'nombre'  => $contacto->nombre_completo,
                'mensaje' => $mensaje,
            ]
        );

        if (!$response->successful()) return;

        $data = $response->json();
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
        ]);
    }
}
