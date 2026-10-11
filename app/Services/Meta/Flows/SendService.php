<?php

namespace App\Services\Meta\Flows;

use Illuminate\Support\Facades\Http;

class SendService
{
    /**
     * Envía un mensaje interactivo de WhatsApp Flows.
     *
     * Permite enviar un Flow mediante su nombre, con encabezado,
     * cuerpo, pie de mensaje y datos iniciales para la pantalla.
     *
     * Importante:
     * Los mensajes de Flow no se entregarán a usuarios que utilicen
     * versiones de WhatsApp que no sean compatibles con esta funcionalidad.
     *
     * Endpoint:
     * POST /{PHONE-NUMBER-ID}/messages
     *
     * Parámetros:
     * - accessToken: Token de acceso de Meta.
     * - phoneNumberId: ID del número de teléfono de WhatsApp Business.
     * - recipientPhoneNumber: Número de WhatsApp del destinatario.
     * - flowToken: Token para identificar la interacción con el Flow.
     * - flowName: Nombre del Flow.
     * - screenId: Identificador de la pantalla inicial.
     * - flowCta: Texto del botón para abrir el Flow.
     * - headerText: Texto del encabezado.
     * - bodyText: Texto principal del mensaje.
     * - footerText: Texto del pie de mensaje.
     * - data: Datos iniciales que recibirá la pantalla.
     *
     * Respuesta exitosa:
     * Devuelve la respuesta de Meta con el identificador del mensaje enviado.
     */
    public function sendFlowMessage(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $flowToken,
        string $flowName,
        string $screenId,
        string $flowCta,
        string $headerText,
        string $bodyText,
        string $footerText,
        array $data = []
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $recipientPhoneNumber,
            'recipient_type' => 'individual',
            'type' => 'interactive',
            'interactive' => [
                'type' => 'flow',
                'header' => [
                    'type' => 'text',
                    'text' => $headerText,
                ],
                'body' => [
                    'text' => $bodyText,
                ],
                'footer' => [
                    'text' => $footerText,
                ],
                'action' => [
                    'name' => 'flow',
                    'parameters' => [
                        'flow_message_version' => '3',
                        'flow_action' => 'navigate',
                        'flow_token' => $flowToken,
                        'flow_name' => $flowName,
                        'flow_cta' => $flowCta,
                        'flow_action_payload' => [
                            'screen' => $screenId,
                            'data' => (object) $data,
                        ],
                    ],
                ],
            ],
        ];

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post(
                "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages",
                $payload
            );

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al enviar el mensaje de WhatsApp Flow.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Crea una plantilla de WhatsApp con un botón Flow identificado por nombre.
     *
     * La plantilla incluye un cuerpo de texto y un botón que permite
     * al usuario abrir una pantalla específica del Flow.
     *
     * Endpoint:
     * POST /{WABA-ID}/message_templates
     *
     * Parámetros:
     * - accessToken: Token de acceso de Meta.
     * - wabaId: ID de la cuenta de WhatsApp Business.
     * - templateName: Nombre único de la plantilla.
     * - language: Idioma de la plantilla.
     * - bodyText: Texto del cuerpo del mensaje.
     * - buttonText: Texto del botón Flow.
     * - flowName: Nombre del Flow existente.
     * - screenId: Identificador de la pantalla de destino.
     * - category: Categoría de la plantilla.
     *
     * Respuesta exitosa:
     * Devuelve la información de la plantilla creada.
     */
    public function createFlowTemplateByName(
        string $accessToken,
        string $wabaId,
        string $templateName,
        string $flowName,
        string $screenId,
        string $bodyText = 'Check out this new offer',
        string $buttonText = 'Check out this offer!',
        string $language = 'en_US',
        string $category = 'MARKETING'
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $payload = [
            'name' => $templateName,
            'language' => $language,
            'category' => $category,
            'components' => [
                [
                    'type' => 'BODY',
                    'text' => $bodyText,
                ],
                [
                    'type' => 'BUTTONS',
                    'buttons' => [
                        [
                            'type' => 'FLOW',
                            'text' => $buttonText,
                            'flow_name' => $flowName,
                            'navigate_screen' => $screenId,
                            'flow_action' => 'navigate',
                        ],
                    ],
                ],
            ],
        ];

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post(
                "https://graph.facebook.com/{$version}/{$wabaId}/message_templates",
                $payload
            );

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al crear la plantilla Flow por nombre.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Crea una plantilla de WhatsApp con un botón Flow definido mediante JSON.
     *
     * El JSON debe contener la definición válida del Flow, incluidas
     * sus pantallas, componentes y acciones.
     *
     * Endpoint:
     * POST /{WABA-ID}/message_templates
     *
     * Parámetros:
     * - accessToken: Token de acceso de Meta.
     * - wabaId: ID de la cuenta de WhatsApp Business.
     * - templateName: Nombre único de la plantilla.
     * - flowJson: Definición del Flow en formato JSON.
     * - screenId: Identificador de la pantalla de destino.
     * - bodyText: Texto del cuerpo del mensaje.
     * - buttonText: Texto del botón Flow.
     * - language: Idioma de la plantilla.
     * - category: Categoría de la plantilla.
     *
     * Respuesta exitosa:
     * Devuelve la información de la plantilla creada.
     */
    public function createFlowTemplateByJson(
        string $accessToken,
        string $wabaId,
        string $templateName,
        array $flowJson,
        string $screenId,
        string $bodyText = 'Check out this new offer',
        string $buttonText = 'Check out this offer!',
        string $language = 'en_US',
        string $category = 'MARKETING'
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $payload = [
            'name' => $templateName,
            'language' => $language,
            'category' => $category,
            'components' => [
                [
                    'type' => 'BODY',
                    'text' => $bodyText,
                ],
                [
                    'type' => 'BUTTONS',
                    'buttons' => [
                        [
                            'type' => 'FLOW',
                            'text' => $buttonText,
                            'flow_json' => json_encode(
                                $flowJson,
                                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                            ),
                            'navigate_screen' => $screenId,
                            'flow_action' => 'navigate',
                        ],
                    ],
                ],
            ],
        ];

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post(
                "https://graph.facebook.com/{$version}/{$wabaId}/message_templates",
                $payload
            );

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al crear la plantilla Flow mediante JSON.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Crea una plantilla de WhatsApp con un botón Flow identificado por ID.
     *
     * La plantilla incluye un cuerpo de texto y un botón que abre
     * una pantalla específica de un Flow existente.
     *
     * Endpoint:
     * POST /{WABA-ID}/message_templates
     *
     * Parámetros:
     * - accessToken: Token de acceso de Meta.
     * - wabaId: ID de la cuenta de WhatsApp Business.
     * - templateName: Nombre único de la plantilla.
     * - flowId: ID del Flow existente.
     * - screenId: Identificador de la pantalla de destino.
     * - bodyText: Texto del cuerpo del mensaje.
     * - buttonText: Texto del botón Flow.
     * - language: Idioma de la plantilla.
     * - category: Categoría de la plantilla.
     *
     * Respuesta exitosa:
     * Devuelve la información de la plantilla creada.
     */
    public function createFlowTemplateById(
        string $accessToken,
        string $wabaId,
        string $templateName,
        string $flowId,
        string $screenId,
        string $bodyText = 'Check out this new offer',
        string $buttonText = 'Check out this offer!',
        string $language = 'en_US',
        string $category = 'MARKETING'
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $payload = [
            'name' => $templateName,
            'language' => $language,
            'category' => $category,
            'components' => [
                [
                    'type' => 'BODY',
                    'text' => $bodyText,
                ],
                [
                    'type' => 'BUTTONS',
                    'buttons' => [
                        [
                            'type' => 'FLOW',
                            'text' => $buttonText,
                            'flow_id' => $flowId,
                            'navigate_screen' => $screenId,
                            'flow_action' => 'navigate',
                        ],
                    ],
                ],
            ],
        ];

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post(
                "https://graph.facebook.com/{$version}/{$wabaId}/message_templates",
                $payload
            );

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al crear la plantilla Flow por ID.',
            'error' => $response->json(),
        ], $response->status());
    }
}
