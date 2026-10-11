<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;

class PaymentsSgService
{
    /**
     * Enviar un mensaje interactivo con los detalles de un pedido por WhatsApp.
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número de WhatsApp del destinatario.
     * @param array $orderDetails Información del pedido y configuración de pago.
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function sendOrderDetailsMessage(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        array $orderDetails
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipientPhoneNumber,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'order_details',
                'order_details' => $orderDetails,
            ],
        ];

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, $payload);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'No fue posible enviar los detalles del pedido por WhatsApp.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Enviar un mensaje interactivo con la actualización del estado de un pedido.
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número de WhatsApp del destinatario.
     * @param string $referenceId Referencia única del pedido original.
     * @param string $status Nuevo estado del pedido.
     * @param string|null $description Descripción opcional del estado.
     * @param string $bodyText Texto principal del mensaje.
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function sendOrderStatusMessage(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $referenceId,
        string $status = 'processing',
        ?string $description = null,
        string $bodyText = 'Te compartimos una actualización sobre tu pedido.'
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        $order = [
            'status' => $status,
        ];

        if ($description !== null && trim($description) !== '') {
            $order['description'] = $description;
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipientPhoneNumber,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'order_status',
                'body' => [
                    'text' => $bodyText,
                ],
                'action' => [
                    'name' => 'review_order',
                    'parameters' => [
                        'reference_id' => $referenceId,
                        'order' => $order,
                    ],
                ],
            ],
        ];

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, $payload);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'No fue posible enviar la actualización del estado del pedido por WhatsApp.',
            'error' => $response->json(),
        ], $response->status());
    }
}
