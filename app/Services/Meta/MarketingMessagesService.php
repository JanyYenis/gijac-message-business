<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;

/**
 * Marketing Messages API for WhatsApp.
 * Doc: https://developers.facebook.com/documentation/business-messaging/whatsapp/marketing-messages/send-marketing-messages
 *
 *  - POST /{PHONE_NUMBER_ID}/marketing_messages  (solo acepta plantillas de categoría MARKETING)
 *  - Mismo formato de envío que Cloud API /messages, más `product_policy`.
 *  - product_policy CLOUD_API_FALLBACK (por defecto): si la cuenta no cumple el onboarding de MM API,
 *    Meta envía el mensaje por Cloud API. STRICT: nunca hace fallback.
 *  - Es una API solo de envío: los mensajes entrantes siguen llegando por el webhook de Cloud API.
 */
class MarketingMessagesService
{
    public const POLITICA_FALLBACK = 'CLOUD_API_FALLBACK';
    public const POLITICA_ESTRICTA = 'STRICT';

    /**
     * @param array $header  parámetros del componente header (ya en formato Cloud API; ubicación como lat/long planos)
     * @param array $body    parámetros del componente body
     * @param array $buttons componentes de botones completos (type=button, sub_type, index, parameters)
     */
    public function enviarPlantilla(
        string $version,
        string $phoneNumberId,
        string $token,
        string $to,
        string $nombre,
        string $idioma,
        array $header = [],
        array $body = [],
        array $buttons = [],
        string $politica = self::POLITICA_FALLBACK
    ): MensajeRespuesta {
        $componentes = [];

        if ($header) {
            if (isset($header[0]['latitude'])) {
                $header = [['type' => 'location', 'location' => $header[0]]];
            }
            $componentes[] = ['type' => 'header', 'parameters' => array_values($header)];
        }

        if ($body) {
            $componentes[] = ['type' => 'body', 'parameters' => array_values($body)];
        }

        foreach ($buttons as $boton) {
            $boton['index'] = (string) ($boton['index'] ?? 0);
            if (($boton['sub_type'] ?? '') === 'flow') {
                // Cloud API espera un objeto JSON ({}), no un arreglo vacío
                foreach ($boton['parameters'] as &$p) {
                    if (isset($p['action']) && $p['action'] === []) {
                        $p['action'] = (object) [];
                    }
                }
                unset($p);
            }
            $componentes[] = $boton;
        }

        $plantilla = [
            'name' => $nombre,
            'language' => ['code' => $idioma],
        ];
        if ($componentes) {
            $plantilla['components'] = $componentes;
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $to,
            'type' => 'template',
            'template' => $plantilla,
            'product_policy' => $politica === self::POLITICA_ESTRICTA ? self::POLITICA_ESTRICTA : self::POLITICA_FALLBACK,
        ];

        $respuesta = Http::withToken($token)
            ->acceptJson()
            ->asJson()
            ->timeout(20)
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/marketing_messages", $payload);

        return new MensajeRespuesta($respuesta);
    }

    /**
     * Estado del onboarding de la WABA en MM API: 'ELIGIBLE' | 'ONBOARDED' | null (sin dato / no disponible).
     * Requiere permisos whatsapp_business_management y whatsapp_business_messaging.
     */
    public function estadoOnboarding(string $version, string $wabaId, string $token): ?string
    {
        try {
            $r = Http::withToken($token)->acceptJson()->timeout(10)
                ->get("https://graph.facebook.com/{$version}/{$wabaId}", ['fields' => 'marketing_messages_onboarding_status']);

            return $r->successful() ? ($r->json('marketing_messages_onboarding_status') ?: null) : null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
