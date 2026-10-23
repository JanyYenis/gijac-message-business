<?php

namespace App\Jobs;

use App\DTO\ParsedWhatsAppMessage;
use App\Models\AutomatizacionN8n;
use App\Models\Chatbots\ChatbotAiAssistant;
use App\Models\Chatbots\ChatbotAiHistorial;
use App\Models\Chatbots\ChatbotConfiguracion as Cfg;
use App\Models\Chatbots\ChatbotFlow;
use App\Models\Chatbots\ChatbotSession;
use App\Models\WhatsappAccount;
use App\Models\WhatsappNumber;
use App\Models\Contacto;
use App\Models\Empresa;
use App\Models\Mensaje;
use App\Models\Plan;
use App\Services\AI\AudioTranscriptionService;
use App\Services\AI\ChatbotAiPromptBuilder;
use App\Services\AI\OllamaService;
use App\Services\Chatbot\ChatbotConfigService;
use App\Services\Chatbot\ChatbotNodeExecutor;
use App\Services\Chatbot\ChatbotSessionResolver;
use App\Services\Mensajes\MessageContentService;
use App\Services\Meta\TypingIndicatorsService;
use App\Services\N8n\N8nService;
use App\Services\WhatsApp\IncomingMessageParser;
use App\Services\WhatsApp\MessageStore;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Throwable;

class WebhookMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $datos;
    protected $waba_id;

    public function __construct($datos, $waba_id)
    {
        $this->datos = $datos;
        $this->waba_id = $waba_id;
    }

    public function handle(): void
    {
        $this->messages($this->datos, $this->waba_id);
    }

    /**
     * Punto de entrada: parsea el payload, persiste el mensaje
     * y decide si debe entrar al chatbot avanzado o al IA básico.
     */
    public function messages($datos, $waba_id)
    {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));
        $config = WhatsappAccount::where('estado', WhatsappAccount::CONECTADO)
            ->where('waba_id', $waba_id)
            ->first();

        if (!$config) {
            return;
        }

        // Número al que escribieron: si no es el principal, el resto del flujo trabaja con ese
        $numeroDestino = $datos['metadata']['phone_number_id'] ?? null;
        $numero = null;
        if ($numeroDestino) {
            $numero = WhatsappNumber::where('phone_number_id', $numeroDestino)
                ->where('waba_id', $waba_id)
                ->where('estado', WhatsappAccount::CONECTADO)
                ->first();

            if ($numero && $numero->phone_number_id !== $config->phone_number_id) {
                $config = $config->replicate(); // copia en memoria, no se guarda
                $config->phone_number_id = $numero->phone_number_id;
                $config->phone_number = $numero->phone_number;
            }
        }

        $token = Crypt::decrypt($config->access_token);
        $api = new WhatsAppCloudApi([
            'from_phone_number_id' => $config->phone_number_id,
            'access_token' => $token,
            'graph_version' => $version,
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
            dispatch(new EnviarPushMensaje($waMsgId, $waTo)); // notificación push a la app móvil
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

        // Clasificación con IA y asignación a un agente cuando el cliente abre una conversación nueva
        if ($contacto && $plan->tieneServicio('clasificacion.ia')) {
            ClasificarChat::evaluar($config->cod_empresa, $contacto->id, $config->phone_number_id, $parsed->body);
        }

        // El bot se puede apagar por número (los números sin registro en whatsapp_numbers siguen activos)
        if ($numero && !$numero->bot_activo) return;

        if (!$contacto) return;

        // Configuración general del chatbot de la empresa (horario, mensajes, palabras clave, orden de motores, transferencia…)
        $this->orquestarChatbot($config, $api, $token, $waFrom, $contacto, $parsed, $waMsgId, $tipoOriginal, $empresa, $plan, $sentAt);
    }

    /**
     * Chatbot avanzado (flujo visual con nodos).
     */
    private function runChatbotAvanzado(WhatsappAccount $config, WhatsAppCloudApi $api, string $waFrom, Contacto $contacto, ParsedWhatsAppMessage $parsed): bool
    {
        $flow = ChatbotFlow::where('cod_empresa', $config->cod_empresa)
            ->where('estado', ChatbotFlow::ACTIVO)
            ->first();

        if (!$flow) {
            return false;
        }

        $resolver = app(ChatbotSessionResolver::class);
        $session = $resolver->resolveSession($flow, $waFrom, $contacto->nombre_completo);
        $nextNode = $resolver->resolveNextNode($flow, $session, $parsed->valorChat, $parsed->body);

        if (!$nextNode) {
            Log::warning("CHATBOT: nextNode nulo para sesión {$session->id}");
            return false;
        }

        app(ChatbotNodeExecutor::class)->execute($flow, $session, $nextNode, $waFrom, $config, $api);

        return true;
    }

    /**
     * Chatbot IA básico (webhook a n8n).
     */
    private function runChatbotWebhook(WhatsappAccount $config, WhatsAppCloudApi $api, string $waFrom, Contacto $contacto, string $tipoOriginal, ParsedWhatsAppMessage $parsed, string $waMsgId, ?string $textoOverride = null): bool
    {
        $mensaje = Mensaje::where('wa_message_id', $waMsgId)->first();

        if (!$mensaje) {
            return false;
        }

        $texto = $textoOverride ?: app(MessageContentService::class)
            ->obtenerTexto($mensaje);

        if (!$texto) {
            return true; // sin texto (imagen, audio sin transcribir…): no hay nada que enviar a la automatización
        }

        $automatizacion = AutomatizacionN8n::query()
            ->where('cod_empresa', $config->cod_empresa)
            ->where('estado', AutomatizacionN8n::ACTIVO)
            ->where('webhook_activo', AutomatizacionN8n::ACTIVO)
            ->first();

        if (!$automatizacion) {
            return false;
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

        if ($data === null || $data === false) {
            return false; // la automatización no respondió / falló
        }
        if (empty($data['output'])) {
            return true; // n8n recibió el mensaje; puede responder por su cuenta
        }
        $responseMensaje = $api->sendTextMessage($waFrom, $data['output']);

        if (!$responseMensaje?->body()) return true;

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

        return true;
    }

    /**
     * Chatbot IA (asistente Ollama configurado en el módulo de Asistente IA).
     */
    private function runChatbotIaBasico(WhatsappAccount $config, WhatsAppCloudApi $api, string $waFrom, Contacto $contacto, ChatbotAiAssistant $asistente, string $waMsgId, ?string $textoOverride = null, bool $mantenerContexto = true): bool
    {
        $capacidades = $asistente->capacidades ?? [];

        if (!in_array(ChatbotAiAssistant::RESPONDER_AUTOMATICAMENTE, $capacidades)) {
            return false; // el asistente existe pero tiene apagada la respuesta automática
        }

        $mensaje = Mensaje::where('wa_message_id', $waMsgId)->first();
        if (!$mensaje) return false;

        $texto = $textoOverride ?: app(MessageContentService::class)->obtenerTexto($mensaje);
        if (!$texto) return true; // sin texto: nada que responder

        // --- Horario ---
        if ($asistente->respetar_horario && !$this->dentroDeHorarioAsistente($asistente)) {
            if ($asistente->mensaje_fuera_horario) {
                $this->enviarYRegistrarRespuestaIa($api, $config, $waFrom, $contacto, $asistente->mensaje_fuera_horario, Mensaje::IA);
            }
            return true;
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
                return true;
            }
        }

        // --- Historial / contexto ---
        $guardarContexto      = $mantenerContexto && in_array(ChatbotAiAssistant::GUARDAR_CONTEXTO, $capacidades);
        $recordarConversacion = $mantenerContexto && in_array(ChatbotAiAssistant::REMEMBRAR_CONVERSACION, $capacidades);

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
            return false;
        }

        if (!$respuesta) return false;

        $this->enviarYRegistrarRespuestaIa($api, $config, $waFrom, $contacto, $respuesta, Mensaje::IA);

        if ($guardarContexto) {
            $this->guardarHistorialAsistente($contacto, $config, $texto, $respuesta);
        }

        return true;
    }

    /* =====================================================================
     * Orquestador: aplica la "Configuración" del chatbot de la empresa
     * ===================================================================== */

    private function orquestarChatbot(WhatsappAccount $config, WhatsAppCloudApi $api, string $token, string $waFrom, Contacto $contacto, ParsedWhatsAppMessage $parsed, string $waMsgId, string $tipoOriginal, Empresa $empresa, Plan $plan, Carbon $sentAt): void
    {
        $cfg = ChatbotConfigService::paraEmpresa($empresa->id);
        if (!$cfg->activo) return;

        $vars = ['nombre' => $contacto->nombre_completo, 'empresa' => $empresa->nombre ?? ''];
        $texto = trim((string) ($parsed->body ?? ''));

        // ¿Conversación nueva? (sin mensajes del cliente en los últimos "tiempo de sesión" minutos)
        $ultimo = Mensaje::where('wa_from', $waFrom)->where('wa_message_id', '!=', $waMsgId)->max('sent_at');
        $nueva = !$ultimo || Carbon::parse($ultimo)->diffInMinutes($sentAt, true) >= max(1, (int) $cfg->tiempo_sesion);
        if ($nueva) {
            $this->iniciarConversacion($cfg, $contacto, $waFrom);
        }

        // Contacto con un agente (o con el bot apagado): el chatbot no interviene
        if ($contacto->estado_chatbot != Contacto::ACTIVO && $contacto->estado_chatbot_ia != Contacto::ACTIVO) return;

        // Respuesta a "¿Quieres hablar con un agente?"
        if (Cache::pull("bot:confirma:{$contacto->id}") && $this->esAfirmativo($texto)) {
            $this->transferir($cfg, $contacto, $config, $api, $waFrom, $vars, Cfg::MOTIVO_PALABRA_CLAVE, true);
            return;
        }

        // Horario de atención
        if (!ChatbotConfigService::dentroDeHorario($cfg)) {
            $fuera = ChatbotConfigService::mensaje($cfg, Cfg::MENSAJE_FUERA_HORARIO, $vars);
            if ($fuera && Cache::add("bot:fh:{$contacto->id}", 1, now()->addMinutes(60))) {
                $this->enviarYRegistrarRespuestaIa($api, $config, $waFrom, $contacto, $fuera, null);
            }
            return;
        }

        if ($nueva && ($bienvenida = ChatbotConfigService::mensaje($cfg, Cfg::MENSAJE_BIENVENIDA, $vars))) {
            $this->enviarYRegistrarRespuestaIa($api, $config, $waFrom, $contacto, $bienvenida, null);
        }

        // Palabras clave / "quiero un asesor" -> transferencia
        if ($cfg->permitir_transferencia && $texto !== '') {
            $motivo = null;
            if (ChatbotConfigService::palabraClave($cfg, $texto) && ChatbotConfigService::motivoActivo($cfg, Cfg::MOTIVO_PALABRA_CLAVE)) {
                $motivo = Cfg::MOTIVO_PALABRA_CLAVE;
            }
            $motivo ??= ChatbotConfigService::motivoPorFrase($cfg, $texto);

            if ($motivo) {
                $porPalabra = $motivo === Cfg::MOTIVO_PALABRA_CLAVE;
                if ($porPalabra && $cfg->accion_palabra_clave == Cfg::PALABRA_PREGUNTAR) {
                    Cache::put("bot:confirma:{$contacto->id}", 1, now()->addMinutes(10));
                    $this->enviarYRegistrarRespuestaIa($api, $config, $waFrom, $contacto, '¿Quieres que te comunique con un asesor? Responde *sí* o *no*.', null);
                    return;
                }
                $this->transferir($cfg, $contacto, $config, $api, $waFrom, $vars, $motivo, !($porPalabra && $cfg->accion_palabra_clave == Cfg::PALABRA_TRANSFERIR));
                return;
            }
        }

        $motores = $this->motoresDisponibles($cfg, $empresa, $plan, $contacto);
        if (!$motores) return;

        // Mensajes consecutivos: se espera unos segundos; si llegó otro más reciente, ese job responderá por todos
        $textoGrupo = null;
        if ($cfg->agrupar_mensajes > 0 && $texto !== '' && !$parsed->valorChat) {
            sleep(min(30, (int) $cfg->agrupar_mensajes));
            $hayNuevo = Mensaje::where('wa_from', $waFrom)->where('estado', Mensaje::ENVIADO)
                ->where('wa_message_id', '!=', $waMsgId)->where('sent_at', '>', $sentAt)->exists();
            if ($hayNuevo) return;
            $textoGrupo = $this->textoAgrupado($waFrom, $config->phone_number_id);
        }

        if ($cfg->mostrar_escribiendo) {
            try {
                app(TypingIndicatorsService::class)->sendTypingIndicatorAndReadReceipt($token, $config->phone_number_id, $waMsgId);
            } catch (Throwable $e) {
                // el indicador es un extra: si falla, se responde igual
            }
        }
        if ($cfg->tiempo_respuesta > 0) {
            sleep(min(10, (int) $cfg->tiempo_respuesta));
        }

        // Motores en el orden elegido por el cliente
        foreach ($motores as $motor) {
            try {
                $resuelto = $this->ejecutarMotor($motor, $config, $api, $waFrom, $contacto, $parsed, $waMsgId, $tipoOriginal, $empresa, $textoGrupo, (bool) $cfg->mantener_contexto);
            } catch (Throwable $e) {
                report($e);
                $this->transferir($cfg, $contacto, $config, $api, $waFrom, $vars, Cfg::MOTIVO_ERROR, true);
                return;
            }

            if ($resuelto) return;
            if ($cfg->accion_respuesta_no_resuelta != Cfg::NO_RESUELTA_SIGUIENTE) break;
        }

        $this->sinResolver($cfg, $contacto, $config, $api, $waFrom, $vars);
    }

    /** Motores que pueden atender a este contacto ahora, en el orden de prioridad configurado. */
    private function motoresDisponibles(Cfg $cfg, Empresa $empresa, Plan $plan, Contacto $contacto): array
    {
        $disponibles = [];
        foreach (ChatbotConfigService::motoresEnOrden($cfg) as $tipo) {
            $ok = match ($tipo) {
                Cfg::MOTOR_NODOS => $plan->tieneServicio('chatbots.avanzados') && $contacto->estado_chatbot == Contacto::ACTIVO && $empresa->nodoActivo()->exists(),
                Cfg::MOTOR_IA => $plan->tieneServicio('chatbots.ia') && $contacto->estado_chatbot_ia == Contacto::ACTIVO
                    && $empresa->asistenteActivo()->exists()
                    && in_array(ChatbotAiAssistant::RESPONDER_AUTOMATICAMENTE, $empresa->asistenteActivo()->first()?->capacidades ?? []),
                Cfg::MOTOR_AUTOMATIZACION => $plan->tieneServicio('chatbot.n8n') && $contacto->estado_chatbot_ia == Contacto::ACTIVO && $empresa->automatizacionN8nActiva()->exists(),
                default => false,
            };
            if ($ok) $disponibles[] = $tipo;
        }

        return $disponibles;
    }

    private function ejecutarMotor(int $motor, WhatsappAccount $config, WhatsAppCloudApi $api, string $waFrom, Contacto $contacto, ParsedWhatsAppMessage $parsed, string $waMsgId, string $tipoOriginal, Empresa $empresa, ?string $textoGrupo, bool $mantenerContexto): bool
    {
        return match ($motor) {
            Cfg::MOTOR_NODOS => $this->runChatbotAvanzado($config, $api, $waFrom, $contacto, $parsed),
            Cfg::MOTOR_IA => $this->runChatbotIaBasico($config, $api, $waFrom, $contacto, $empresa->asistenteActivo()->first(), $waMsgId, $textoGrupo, $mantenerContexto),
            Cfg::MOTOR_AUTOMATIZACION => $this->runChatbotWebhook($config, $api, $waFrom, $contacto, $tipoOriginal, $parsed, $waMsgId, $textoGrupo),
            default => false,
        };
    }

    /** Lo que hace el chatbot cuando ningún motor pudo resolver (opción "Respuestas no resueltas"). */
    private function sinResolver(Cfg $cfg, Contacto $contacto, WhatsappAccount $config, WhatsAppCloudApi $api, string $waFrom, array $vars): void
    {
        switch ((int) $cfg->accion_respuesta_no_resuelta) {
            case Cfg::NO_RESUELTA_REINTENTAR:
                if (Cache::add("bot:reintento:{$contacto->id}", 1, now()->addMinutes(10))) {
                    $this->enviarYRegistrarRespuestaIa($api, $config, $waFrom, $contacto, 'No logré entender tu consulta. ¿Podrías contármela de nuevo con otras palabras?', null);
                    break;
                }
                $this->transferir($cfg, $contacto, $config, $api, $waFrom, $vars, Cfg::MOTIVO_NO_RESUELVE, true);
                break;
            case Cfg::NO_RESUELTA_MENSAJE:
                if (trim((string) $cfg->mensaje_no_resuelto) !== '') {
                    $this->enviarYRegistrarRespuestaIa($api, $config, $waFrom, $contacto, $cfg->mensaje_no_resuelto, null);
                }
                break;
            default: // transferir (o "siguiente motor" ya agotados)
                $this->transferir($cfg, $contacto, $config, $api, $waFrom, $vars, Cfg::MOTIVO_NO_RESUELVE, true);
        }
    }

    /**
     * Pasa la conversación a un agente: apaga el bot para ese contacto, avisa (opcional) y programa la espera de agente.
     * No hace nada si las transferencias están apagadas o ese motivo está desactivado.
     */
    private function transferir(Cfg $cfg, Contacto $contacto, WhatsappAccount $config, WhatsAppCloudApi $api, string $waFrom, array $vars, int $motivo, bool $conMensaje): bool
    {
        if (!$cfg->permitir_transferencia || !ChatbotConfigService::motivoActivo($cfg, $motivo)) {
            return false;
        }

        if ($conMensaje && ($m = ChatbotConfigService::mensaje($cfg, Cfg::MENSAJE_TRANSFERENCIA, $vars))) {
            $this->enviarYRegistrarRespuestaIa($api, $config, $waFrom, $contacto, $m, null);
        }

        $contacto->update(['estado_chatbot' => Contacto::INACTIVO, 'estado_chatbot_ia' => Contacto::INACTIVO]);
        Cache::put("bot:transferido:{$contacto->id}", 1, now()->addDays(7));

        ChatbotSession::where('telefono_contacto', $waFrom)->whereIn('estado', [ChatbotSession::ACTIVO, ChatbotSession::ESPERANDO])
            ->update(['estado' => ChatbotSession::AGENTE, 'fecha_finalizado' => now()]);

        if ($cfg->tipo_reinicio_conversacion == Cfg::REINICIO_TRANSFERENCIA) {
            ChatbotAiHistorial::where('contacto_id', $contacto->id)->delete();
        }

        if ($cfg->tiempo_espera_agente > 0 && in_array((int) $cfg->accion_sin_agente, [Cfg::SIN_AGENTE_MENSAJE, Cfg::SIN_AGENTE_CERRAR], true)) {
            ChatbotEsperaAgente::dispatch($contacto->id, $config->id, $config->phone_number_id, now()->format('Y-m-d H:i:s'), (int) $cfg->accion_sin_agente)
                ->delay(now()->addMinutes((int) $cfg->tiempo_espera_agente));
        }

        return true;
    }

    /** Nueva conversación tras inactividad: se olvida el contexto y, si el contacto estaba transferido, el bot vuelve a atenderlo. */
    private function iniciarConversacion(Cfg $cfg, Contacto $contacto, string $waFrom): void
    {
        if ($cfg->tipo_reinicio_conversacion != Cfg::REINICIO_INACTIVIDAD) return;

        ChatbotAiHistorial::where('contacto_id', $contacto->id)->delete();
        ChatbotSession::where('telefono_contacto', $waFrom)->whereIn('estado', [ChatbotSession::ACTIVO, ChatbotSession::ESPERANDO])
            ->update(['estado' => ChatbotSession::EXPIRADO, 'fecha_finalizado' => now()]);

        // Solo se reactiva si fue el propio chatbot quien lo transfirió (no si alguien lo apagó a mano)
        if (Cache::pull("bot:transferido:{$contacto->id}")) {
            $contacto->update(['estado_chatbot' => Contacto::ACTIVO, 'estado_chatbot_ia' => Contacto::ACTIVO]);
        }
    }

    private function esAfirmativo(string $texto): bool
    {
        $t = ChatbotConfigService::normalizar($texto);

        return $t !== '' && (bool) preg_match('/^(si|sii+|claro|dale|ok|okay|yes|por favor|quiero|listo|de una)\b/', $t);
    }

    /** Mensajes del cliente desde la última respuesta que se le envió, como una sola consulta. */
    private function textoAgrupado(string $waFrom, string $phoneNumberId): ?string
    {
        $ultimaSalida = Mensaje::where('wa_from', $phoneNumberId)->where('wa_to', $waFrom)->max('sent_at');

        $textos = Mensaje::where('wa_from', $waFrom)->where('estado', Mensaje::ENVIADO)
            ->when($ultimaSalida, fn ($q) => $q->where('sent_at', '>', $ultimaSalida))
            ->orderBy('sent_at')->get()
            ->map(fn ($m) => app(MessageContentService::class)->obtenerTexto($m))
            ->filter()->values();

        return $textos->count() > 1 ? $textos->implode("\n") : null;
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

    private function guardarHistorialAsistente(Contacto $contacto, WhatsappAccount $config, string $textoUsuario, string $respuestaAsistente): void
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

    private function enviarYRegistrarRespuestaIa(WhatsAppCloudApi $api, WhatsappAccount $config, string $waFrom, Contacto $contacto, string $texto, ?int $responseType): void
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
