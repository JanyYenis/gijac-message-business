<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;

class TypingIndicatorsService
{
    /**
     * Marcar un mensaje de WhatsApp como leído y enviar el indicador de escritura.
     *
     * Envía una solicitud a la API de WhatsApp Cloud para marcar un mensaje
     * como leído y mostrar el indicador de escritura mientras se prepara
     * una respuesta.
     *
     * Endpoint:
     * POST /{phone-number-id}/messages
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp Business.
     * @param string $messageId ID del mensaje entrante de WhatsApp.
     * @return \Illuminate\Http\JsonResponse|array
     */
    public function sendTypingIndicatorAndReadReceipt(
        string $accessToken,
        string $phoneNumberId,
        string $messageId
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'status' => 'read',
                'message_id' => $messageId,
                'typing_indicator' => [
                    'type' => 'text',
                ],
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al marcar el mensaje como leído y enviar el indicador de escritura.',
            'error' => $response->json(),
        ], $response->status());
    }
}
