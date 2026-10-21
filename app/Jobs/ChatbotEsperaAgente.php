<?php

namespace App\Jobs;

use App\Models\Chatbots\ChatbotConfiguracion as Cfg;
use App\Models\Contacto;
use App\Models\Mensaje;
use App\Models\WhatsappAccount;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Netflie\WhatsAppCloudApi\WhatsAppCloudApi;
use Throwable;

/**
 * Se programa al transferir un chat a un agente. Pasado el "tiempo máximo de espera", si ningún agente
 * respondió aplica la opción "Si ningún agente está disponible" de la configuración del chatbot.
 */
class ChatbotEsperaAgente implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;

    public function __construct(
        public string $contactoId,
        public string $codConfig,
        public string $phoneNumberId,
        public string $desde,   // momento de la transferencia (Y-m-d H:i:s)
        public int $accion      // Cfg::SIN_AGENTE_MENSAJE | Cfg::SIN_AGENTE_CERRAR
    ) {
    }

    public function handle(): void
    {
        try {
            $contacto = Contacto::find($this->contactoId);
            $cuenta = WhatsappAccount::find($this->codConfig);
            if (!$contacto || !$cuenta) {
                return;
            }

            // Un agente ya respondió: no hay nada que hacer
            $atendido = Mensaje::where('wa_from', $this->phoneNumberId)
                ->where('wa_to', $contacto->numero_completo)
                ->where('response_type', Mensaje::HUMANA)
                ->where('sent_at', '>=', $this->desde)
                ->exists();
            if ($atendido) {
                return;
            }

            if ($this->accion == Cfg::SIN_AGENTE_MENSAJE) {
                $this->enviar($cuenta, $contacto, 'Por ahora todos nuestros asesores están ocupados. Te responderemos lo antes posible.');
            } elseif ($this->accion == Cfg::SIN_AGENTE_CERRAR) {
                $this->enviar($cuenta, $contacto, 'Cerramos esta atención por ahora. Cuando nos escribas de nuevo, con gusto te ayudaremos.');
                // La atención termina: el chatbot vuelve a atender al contacto en su próximo mensaje
                $contacto->update(['estado_chatbot' => Contacto::ACTIVO, 'estado_chatbot_ia' => Contacto::ACTIVO]);
                Cache::forget("bot:transferido:{$contacto->id}");
            }
        } catch (Throwable $e) {
            Log::warning('Espera de agente fallida', ['contacto' => $this->contactoId, 'error' => $e->getMessage()]);
        }
    }

    private function enviar(WhatsappAccount $cuenta, Contacto $contacto, string $texto): void
    {
        $api = new WhatsAppCloudApi([
            'from_phone_number_id' => $this->phoneNumberId,
            'access_token' => Crypt::decrypt($cuenta->access_token),
            'graph_version' => config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0')),
        ]);

        $respuesta = $api->sendTextMessage($contacto->numero_completo, $texto);
        if (!$respuesta?->body()) {
            return;
        }
        $body = json_decode($respuesta->body());

        Mensaje::create([
            'campaign_id' => null,
            'contact_id' => $contacto->id,
            'wa_message_id' => $body->messages[0]->id ?? null,
            'wa_from' => $this->phoneNumberId,
            'wa_to' => $contacto->numero_completo,
            'type' => Mensaje::TEXTO,
            'body' => $texto,
            'metadata' => null,
            'estado' => Mensaje::ENVIADO,
            'sent_at' => now(),
            'response_type' => null,
        ]);
    }
}
