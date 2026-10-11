<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;

class PaymentsInService
{
    /**
     * Envía un mensaje interactivo de detalles de pedido con opción de pago
     * mediante el flujo de pagos de India (UPI) de WhatsApp Cloud API.
     *
     * Requiere acceso habilitado por Meta para las API beta de pagos.
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número de WhatsApp del destinatario.
     * @param array $parameters Datos del pedido y configuración del pago.
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function sendOrderDetailsMessageIndia(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        array $parameters
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
                'header' => $parameters['header'] ?? null,
                'body' => [
                    'text' => $parameters['body_text'] ?? 'Detalles de tu pedido.',
                ],
                'footer' => [
                    'text' => $parameters['footer_text'] ?? 'Gracias por tu compra.',
                ],
                'action' => [
                    'name' => 'review_and_pay',
                    'parameters' => [
                        'reference_id' => $parameters['reference_id'],
                        'type' => $parameters['type'] ?? 'digital-goods',
                        'payment_type' => 'upi',
                        'payment_configuration' => $parameters['payment_configuration'],
                        'currency' => 'INR',
                        'total_amount' => $parameters['total_amount'],
                        'order' => $parameters['order'],
                    ],
                ],
            ],
        ];

        // Elimina el encabezado si no se ha configurado.
        if ($payload['interactive']['header'] === null) {
            unset($payload['interactive']['header']);
        }

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, $payload);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'No fue posible enviar los detalles del pedido con el flujo de pagos de India.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Enviar un mensaje interactivo con la actualización del estado de un pedido
     * mediante el flujo de pagos de India (IN) de WhatsApp Cloud API.
     *
     * Requiere acceso habilitado por Meta para las API beta de pagos.
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número de WhatsApp del destinatario.
     * @param string $referenceId Referencia del pedido original.
     * @param string $status Nuevo estado del pedido.
     * @param string|null $description Descripción opcional del estado.
     * @param string $bodyText Texto principal del mensaje.
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function sendOrderStatusMessageIndia(
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
            'message' => 'No fue posible enviar la actualización del estado del pedido mediante WhatsApp.',
            'error' => $response->json(),
        ], $response->status());
    }
}
