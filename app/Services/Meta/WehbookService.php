<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;

class WehbookService
{
    /**
     * Suscribe una aplicación a una cuenta de WhatsApp Business (WABA).
     *
     * Para obtener más información sobre cómo suscribir una aplicación
     * a una cuenta de WhatsApp Business, consulta la documentación oficial:
     *
     * https://developers.facebook.com/docs/whatsapp/embedded-signup/webhooks#subscribe-to-a-whatsapp-business-account
     */

    public function subscribeWaba(string $version, string $accessToken, string $wabaId)
    {
        $response = Http::withToken($accessToken)
            ->post("https://graph.facebook.com/{$version}/{$wabaId}/subscribed_apps");

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'status' => $response->status(),
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Obtiene la lista de aplicaciones suscritas a Webhooks para una
     * cuenta de WhatsApp Business (WABA).
     *
     * Para consultar las aplicaciones suscritas, se realiza una petición
     * GET al endpoint subscribed_apps de la cuenta de WhatsApp Business.
     */
    public function getSubscribedApps(string $version, string $accessToken, string $wabaId)
    {
        $response = Http::withToken($accessToken)
            ->get("https://graph.facebook.com/{$version}/{$wabaId}/subscribed_apps");

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al obtener las aplicaciones suscritas a la cuenta de WhatsApp Business.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Cancela la suscripción de la aplicación a los Webhooks de una
     * cuenta de WhatsApp Business (WABA).
     *
     * Para cancelar la suscripción, se realiza una petición DELETE al
     * endpoint subscribed_apps de la cuenta de WhatsApp Business.
     *
     * Al ejecutar esta operación, la aplicación dejará de recibir
     * notificaciones de Webhooks asociadas a la WABA.
     */
    public function unsubscribeWaba(string $version, string $accessToken, string $wabaId)
    {
        $response = Http::withToken($accessToken)
            ->delete("https://graph.facebook.com/{$version}/{$wabaId}/subscribed_apps");

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al cancelar la suscripción de la aplicación a los Webhooks de la cuenta de WhatsApp Business.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Suscribe una aplicación a los Webhooks de una cuenta de WhatsApp
     * Business (WABA) utilizando una URL de callback alternativa.
     *
     * Esta opción es útil cuando se tienen varias cuentas de WhatsApp
     * Business y se desea que las notificaciones de Webhooks de cada WABA
     * sean enviadas a diferentes URLs de callback.
     *
     * Antes de utilizar una URL alternativa, se debe verificar que el
     * endpoint pueda recibir y procesar correctamente las notificaciones
     * de Webhooks de Meta.
     *
     * Parámetros:
     *
     * - override_callback_uri: URL del endpoint alternativo que recibirá
     *   las notificaciones de Webhooks de esta WABA.
     *
     * - verify_token: Token utilizado para verificar el endpoint alternativo
     *   durante el proceso de configuración del Webhook.
     *
     * La solicitud se realiza mediante POST al endpoint:
     *
     * {{WABA-ID}}/subscribed_apps
     *
     * Referencia:
     * https://developers.facebook.com/docs/whatsapp/embedded-signup/webhooks#overriding-the-callback-url
     */
    public function subscribeWabaWithCallback(string $version, string $accessToken, string $wabaId, string $callbackUrl, string $verifyToken) {
        $response = Http::withToken($accessToken)
            ->post("https://graph.facebook.com/{$version}/{$wabaId}/subscribed_apps", [
                'override_callback_uri' => $callbackUrl,
                'verify_token' => $verifyToken,
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al suscribir la aplicación a los Webhooks con la URL de callback alternativa.',
            'error' => $response->json(),
        ], $response->status());
    }
}
