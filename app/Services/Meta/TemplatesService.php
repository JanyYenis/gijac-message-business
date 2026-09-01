<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;

class TemplatesService
{
    /**
     * Obtiene la información de una plantilla de mensajes de WhatsApp
     * utilizando el ID de la plantilla.
     *
     * Esta consulta permite obtener información relacionada con la plantilla,
     * incluyendo datos utilizados para el seguimiento y control de su calidad.
     *
     * Guías oficiales:
     *
     * - Plantillas de mensajes:
     * https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates
     *
     * - Monitoreo de señales de calidad:
     * https://developers.facebook.com/docs/whatsapp/guides/how-to-monitor-quality-signals
     *
     * Referencia del endpoint:
     * https://developers.facebook.com/docs/graph-api/reference/whats-app-business-hsm/
     */
    public function getMessageTemplate(string $version, string $accessToken, string $templateId)
    {
        $response = Http::withToken($accessToken)
            ->get("https://graph.facebook.com/{$version}/{$templateId}");

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al obtener la información de la plantilla de mensajes.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Obtiene las plantillas de mensajes de una cuenta de WhatsApp Business
     * (WABA), permitiendo filtrar los resultados por el nombre de la plantilla.
     *
     * Parámetros:
     *
     * - name: Nombre de la plantilla que se desea consultar.
     *   Este parámetro es opcional y permite obtener únicamente las plantillas
     *   que coincidan con el nombre indicado.
     *
     * Guías oficiales:
     *
     * 1. Plantillas de mensajes:
     * https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates
     *
     * 2. Monitoreo de señales de calidad:
     * https://developers.facebook.com/docs/whatsapp/guides/how-to-monitor-quality-signals
     *
     * Referencia del endpoint:
     * https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account/message_templates/
     */
    public function getMessageTemplates(string $version, string $accessToken, string $wabaId, ?string $name = null)
    {
        $params = [];

        if ($name) {
            $params['name'] = $name;
        }

        $response = Http::withToken($accessToken)
            ->get("https://graph.facebook.com/{$version}/{$wabaId}/message_templates", $params);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al obtener las plantillas de mensajes de WhatsApp.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Obtiene el namespace utilizado por las plantillas de mensajes
     * de una cuenta de WhatsApp Business (WABA).
     *
     * Para consultar únicamente este dato se utiliza el parámetro:
     *
     * - message_template_namespace: Namespace asociado a las plantillas
     *   de mensajes de la cuenta de WhatsApp Business.
     *
     * Guías oficiales:
     *
     * 1. Plantillas de mensajes:
     * https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates
     *
     * 2. Monitoreo de señales de calidad:
     * https://developers.facebook.com/docs/whatsapp/guides/how-to-monitor-quality-signals
     *
     * Referencia del endpoint:
     * https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account/message_templates/
     */
    public function getMessageTemplateNamespace(string $version, string $accessToken, string $wabaId)
    {
        $response = Http::withToken($accessToken)
            ->get("https://graph.facebook.com/{$version}/{$wabaId}", [
                'fields' => 'message_template_namespace',
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al obtener el namespace de las plantillas de mensajes.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Crea una plantilla de mensajes de WhatsApp Business asociada a una
     * cuenta de WhatsApp Business (WABA).
     *
     * Este endpoint permite crear diferentes tipos de plantillas, incluyendo
     * plantillas de autenticación con botones OTP (One-Time Password).
     *
     * Parámetros principales:
     *
     * - name: Nombre único que tendrá la plantilla.
     *
     * - language: Idioma en el que se creará la plantilla.
     *   Por ejemplo: en_US.
     *
     * - category: Categoría de la plantilla.
     *   En este caso se utiliza AUTHENTICATION para plantillas destinadas
     *   a códigos de autenticación.
     *
     * - components: Componentes que conforman la plantilla, como el cuerpo,
     *   pie de página y botones.
     *
     * Para plantillas de autenticación se pueden utilizar:
     *
     * - BODY:
     *   Permite habilitar una recomendación de seguridad mediante
     *   add_security_recommendation.
     *
     * - FOOTER:
     *   Permite establecer el tiempo de expiración del código mediante
     *   code_expiration_minutes.
     *
     * - BUTTONS:
     *   Permite agregar un botón OTP para que el usuario pueda copiar
     *   el código de autenticación.
     *
     *   - type: OTP.
     *   - otp_type: COPY_CODE.
     *   - text: Texto que se mostrará en el botón.
     *
     * Guías oficiales:
     *
     * 1. Plantillas de autenticación con botones OTP:
     * https://developers.facebook.com/docs/whatsapp/business-management-api/authentication-templates
     *
     * 2. Plantillas de mensajes:
     * https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates
     *
     * 3. Monitoreo de señales de calidad:
     * https://developers.facebook.com/docs/whatsapp/guides/how-to-monitor-quality-signals
     *
     * Referencia del endpoint:
     * https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account/message_templates/
     */
    public function createAuthenticationTemplate(string $version, string $accessToken, string $wabaId, string $name, string $language = 'en_US', int $codeExpirationMinutes = 10, string $buttonText = 'Copy Code')
    {
        $response = Http::withToken($accessToken)
            ->post("https://graph.facebook.com/{$version}/{$wabaId}/message_templates", [
                'name' => $name,
                'language' => $language,
                'category' => 'AUTHENTICATION',
                'components' => [
                    [
                        'type' => 'BODY',
                        'add_security_recommendation' => true,
                    ],
                    [
                        'type' => 'FOOTER',
                        'code_expiration_minutes' => $codeExpirationMinutes,
                    ],
                    [
                        'type' => 'BUTTONS',
                        'buttons' => [
                            [
                                'type' => 'OTP',
                                'otp_type' => 'COPY_CODE',
                                'text' => $buttonText,
                            ],
                        ],
                    ],
                ],
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al crear la plantilla de autenticación de WhatsApp.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Crea una plantilla de autenticación de WhatsApp Business con un
     * botón OTP de tipo ONE_TAP para autocompletar el código de verificación.
     *
     * Este tipo de plantilla permite que el usuario pueda autocompletar
     * el código OTP directamente desde la aplicación, facilitando el proceso
     * de autenticación.
     *
     * Parámetros principales:
     *
     * - name: Nombre único de la plantilla.
     *
     * - language: Idioma de la plantilla.
     *   Por ejemplo: en_US.
     *
     * - category: Categoría de la plantilla.
     *   Para este caso debe ser AUTHENTICATION.
     *
     * - components: Componentes que conforman la plantilla.
     *
     *   - BODY:
     *     add_security_recommendation permite incluir una recomendación
     *     de seguridad en el mensaje de autenticación.
     *
     *   - FOOTER:
     *     code_expiration_minutes establece el tiempo de expiración
     *     del código OTP.
     *
     *   - BUTTONS:
     *     Permite configurar el botón OTP de tipo ONE_TAP.
     *
     *     - type: OTP.
     *     - otp_type: ONE_TAP.
     *     - text: Texto del botón.
     *     - autofill_text: Texto utilizado para la opción de autocompletado.
     *     - package_name: Nombre del paquete de la aplicación Android
     *       que utilizará el autocompletado.
     *     - signature_hash: Hash de firma de la aplicación Android.
     *
     * El botón ONE_TAP está orientado a facilitar la recepción y
     * autocompletado de códigos OTP en aplicaciones móviles compatibles.
     *
     * Guías oficiales:
     *
     * 1. Plantillas de autenticación con botones OTP:
     * https://developers.facebook.com/docs/whatsapp/business-management-api/authentication-templates
     *
     * 2. Plantillas de mensajes:
     * https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates
     *
     * 3. Monitoreo de señales de calidad:
     * https://developers.facebook.com/docs/whatsapp/guides/how-to-monitor-quality-signals
     *
     * Referencia del endpoint:
     * https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account/message_templates/
     */
    public function createAuthenticationOneTapTemplate(
        string $version,
        string $accessToken,
        string $wabaId,
        string $name,
        string $packageName,
        string $signatureHash,
        string $language = 'en_US',
        int $codeExpirationMinutes = 10,
        string $buttonText = 'Copy Code',
        string $autofillText = 'Autofill'
    ) {
        $response = Http::withToken($accessToken)
            ->post("https://graph.facebook.com/{$version}/{$wabaId}/message_templates", [
                'name' => $name,
                'language' => $language,
                'category' => 'AUTHENTICATION',
                'components' => [
                    [
                        'type' => 'BODY',
                        'add_security_recommendation' => true,
                    ],
                    [
                        'type' => 'FOOTER',
                        'code_expiration_minutes' => $codeExpirationMinutes,
                    ],
                    [
                        'type' => 'BUTTONS',
                        'buttons' => [
                            [
                                'type' => 'OTP',
                                'otp_type' => 'ONE_TAP',
                                'text' => $buttonText,
                                'autofill_text' => $autofillText,
                                'package_name' => $packageName,
                                'signature_hash' => $signatureHash,
                            ],
                        ],
                    ],
                ],
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al crear la plantilla de autenticación ONE_TAP de WhatsApp.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Crea una plantilla de mensajes de WhatsApp Business de tipo MARKETING
     * con un botón de catálogo.
     *
     * Este tipo de plantilla permite promocionar productos y dirigir al
     * usuario directamente al catálogo de WhatsApp mediante un botón
     * de tipo CATALOG.
     *
     * Parámetros principales:
     *
     * - name: Nombre único de la plantilla.
     *
     * - language: Idioma en el que se crea la plantilla.
     *
     * - category: Categoría de la plantilla. Para este caso se utiliza
     *   MARKETING.
     *
     * - components: Componentes que conforman la plantilla.
     *
     *   - BODY:
     *     Contiene el texto principal del mensaje y permite utilizar
     *     variables dinámicas mediante marcadores como {{1}}, {{2}}, etc.
     *
     *     El objeto example contiene los valores de ejemplo utilizados
     *     para cada variable durante la creación y revisión de la plantilla.
     *
     *   - FOOTER:
     *     Texto que se muestra en la parte inferior del mensaje.
     *
     *   - BUTTONS:
     *     Permite agregar un botón para abrir el catálogo de productos.
     *
     *     - type: CATALOG.
     *     - text: Texto que se mostrará en el botón.
     *
     * Guías oficiales:
     *
     * - Plantillas de catálogo:
     * https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates/catalog-templates
     *
     * - Plantillas de mensajes:
     * https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates
     *
     * Referencia del endpoint:
     * https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account/message_templates/
     */
    public function createCatalogTemplate(
        string $version,
        string $accessToken,
        string $wabaId,
        string $name,
        string $bodyText,
        array $bodyExamples,
        string $footerText,
        string $buttonText = 'View catalog',
        string $language = 'en_US'
    ) {
        $response = Http::withToken($accessToken)
            ->post("https://graph.facebook.com/{$version}/{$wabaId}/message_templates", [
                'name' => $name,
                'language' => $language,
                'category' => 'MARKETING',
                'components' => [
                    [
                        'type' => 'BODY',
                        'text' => $bodyText,
                        'example' => [
                            'body_text' => [
                                $bodyExamples,
                            ],
                        ],
                    ],
                    [
                        'type' => 'FOOTER',
                        'text' => $footerText,
                    ],
                    [
                        'type' => 'BUTTONS',
                        'buttons' => [
                            [
                                'type' => 'CATALOG',
                                'text' => $buttonText,
                            ],
                        ],
                    ],
                ],
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al crear la plantilla de catálogo de WhatsApp.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Crea una plantilla de mensajes de WhatsApp Business de tipo MARKETING
     * con un botón Multi-Product Message (MPM).
     *
     * Este tipo de plantilla permite mostrar múltiples productos al usuario
     * y facilitar el acceso a los artículos disponibles en un catálogo.
     *
     * Parámetros principales:
     *
     * - name: Nombre único de la plantilla.
     *
     * - language: Idioma en el que se crea la plantilla.
     *
     * - category: Categoría de la plantilla. Para este caso se utiliza
     *   MARKETING.
     *
     * - components: Componentes que conforman la plantilla.
     *
     *   - HEADER:
     *     Permite mostrar un encabezado de tipo texto y utilizar variables
     *     dinámicas mediante marcadores como {{1}}.
     *
     *     - format: Debe ser TEXT.
     *     - text: Texto del encabezado.
     *     - example: Valores de ejemplo para las variables utilizadas.
     *
     *   - BODY:
     *     Contiene el mensaje principal de la plantilla y permite utilizar
     *     variables dinámicas mediante marcadores como {{1}}, {{2}}, etc.
     *
     *     El objeto example contiene los valores de ejemplo utilizados
     *     para las variables del cuerpo.
     *
     *   - BUTTONS:
     *     Permite agregar un botón para mostrar múltiples productos.
     *
     *     - type: MPM (Multi-Product Message).
     *     - text: Texto que se mostrará en el botón.
     *
     * Guías oficiales:
     *
     * 1. Plantillas Multi-Product Message:
     * https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates/mpm-templates
     *
     * 2. Plantillas de mensajes:
     * https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates
     *
     * 3. Monitoreo de señales de calidad:
     * https://developers.facebook.com/docs/whatsapp/guides/how-to-monitor-quality-signals
     *
     * Referencia del endpoint:
     * https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account/message_templates/
     */
    public function createMultiProductTemplate(
        string $version,
        string $accessToken,
        string $wabaId,
        string $name,
        string $headerText,
        string $headerExample,
        string $bodyText,
        array $bodyExamples,
        string $buttonText = 'View items',
        string $language = 'en_US'
    ) {
        $response = Http::withToken($accessToken)
            ->post("https://graph.facebook.com/{$version}/{$wabaId}/message_templates", [
                'name' => $name,
                'language' => $language,
                'category' => 'MARKETING',
                'components' => [
                    [
                        'type' => 'HEADER',
                        'format' => 'TEXT',
                        'text' => $headerText,
                        'example' => [
                            'header_text' => [
                                $headerExample,
                            ],
                        ],
                    ],
                    [
                        'type' => 'BODY',
                        'text' => $bodyText,
                        'example' => [
                            'body_text' => [
                                $bodyExamples,
                            ],
                        ],
                    ],
                    [
                        'type' => 'BUTTONS',
                        'buttons' => [
                            [
                                'type' => 'MPM',
                                'text' => $buttonText,
                            ],
                        ],
                    ],
                ],
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al crear la plantilla Multi-Product Message de WhatsApp.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Crea una plantilla de mensajes de WhatsApp Business de tipo MARKETING
     * utilizando texto, variables dinámicas y botones de respuesta rápida.
     *
     * La plantilla puede incluir diferentes componentes:
     *
     * - HEADER:
     *   Encabezado de texto que permite utilizar variables dinámicas
     *   mediante marcadores como {{1}}.
     *
     *   - format: Debe ser TEXT.
     *   - text: Texto del encabezado.
     *   - example: Valores de ejemplo para las variables utilizadas.
     *
     * - BODY:
     *   Contiene el mensaje principal de la plantilla y permite utilizar
     *   múltiples variables dinámicas mediante marcadores como {{1}},
     *   {{2}}, {{3}}, etc.
     *
     *   El objeto example contiene los valores de ejemplo utilizados
     *   para las variables del cuerpo.
     *
     * - FOOTER:
     *   Texto informativo que se muestra en la parte inferior del mensaje.
     *
     * - BUTTONS:
     *   Permite agregar botones de respuesta rápida (QUICK_REPLY).
     *
     *   Cada botón puede utilizarse para que el usuario seleccione una
     *   opción directamente desde el mensaje.
     *
     *   - type: QUICK_REPLY.
     *   - text: Texto que se mostrará en el botón.
     *
     * Guías oficiales:
     *
     * 1. Plantillas de mensajes:
     * https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates
     *
     * 2. Monitoreo de señales de calidad:
     * https://developers.facebook.com/docs/whatsapp/guides/how-to-monitor-quality-signals
     *
     * Referencia del endpoint:
     * https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account/message_templates/
     */
    public function createMarketingQuickReplyTemplate(
        string $version,
        string $accessToken,
        string $wabaId,
        string $name,
        string $headerText,
        string $headerExample,
        string $bodyText,
        array $bodyExamples,
        string $footerText,
        array $buttons,
        string $language = 'en_US'
    ) {
        $quickReplyButtons = [];

        foreach ($buttons as $button) {
            $quickReplyButtons[] = [
                'type' => 'QUICK_REPLY',
                'text' => $button,
            ];
        }

        $response = Http::withToken($accessToken)
            ->post("https://graph.facebook.com/{$version}/{$wabaId}/message_templates", [
                'name' => $name,
                'language' => $language,
                'category' => 'MARKETING',
                'components' => [
                    [
                        'type' => 'HEADER',
                        'format' => 'TEXT',
                        'text' => $headerText,
                        'example' => [
                            'header_text' => [
                                $headerExample,
                            ],
                        ],
                    ],
                    [
                        'type' => 'BODY',
                        'text' => $bodyText,
                        'example' => [
                            'body_text' => [
                                $bodyExamples,
                            ],
                        ],
                    ],
                    [
                        'type' => 'FOOTER',
                        'text' => $footerText,
                    ],
                    [
                        'type' => 'BUTTONS',
                        'buttons' => $quickReplyButtons,
                    ],
                ],
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al crear la plantilla de marketing con respuestas rápidas.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Crea una plantilla de mensajes de WhatsApp Business de tipo MARKETING
     * con encabezado de imagen, variables dinámicas y botones de acción.
     *
     * La plantilla puede incluir los siguientes componentes:
     *
     * - HEADER:
     *   Encabezado de tipo imagen.
     *
     *   - format: IMAGE.
     *   - example: Identificador de la imagen que se utilizará como ejemplo
     *     durante la creación y revisión de la plantilla.
     *
     * - BODY:
     *   Contiene el mensaje principal y permite utilizar variables dinámicas
     *   mediante marcadores como {{1}}, {{2}}, {{3}}, etc.
     *
     *   El objeto example contiene valores de ejemplo para las variables
     *   utilizadas en el cuerpo.
     *
     * - FOOTER:
     *   Texto informativo que se muestra en la parte inferior del mensaje.
     *
     * - BUTTONS:
     *   Permite agregar botones para que el usuario pueda realizar acciones.
     *
     *   - PHONE_NUMBER:
     *     Permite agregar un botón para realizar una llamada telefónica.
     *
     *     - text: Texto del botón.
     *     - phone_number: Número telefónico al que se realizará la llamada.
     *
     *   - URL:
     *     Permite agregar un botón que dirige al usuario a una dirección web.
     *
     *     La URL puede contener variables dinámicas como {{1}}.
     *     El array example contiene los valores utilizados para las variables
     *     de la URL durante la creación de la plantilla.
     *
     * Guías oficiales:
     *
     * 1. Plantillas de mensajes:
     * https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates
     *
     * 2. Monitoreo de señales de calidad:
     * https://developers.facebook.com/docs/whatsapp/guides/how-to-monitor-quality-signals
     *
     * Referencia del endpoint:
     * https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account/message_templates/
     */
    public function createMarketingImageTemplate(
        string $version,
        string $accessToken,
        string $wabaId,
        string $name,
        string $headerHandle,
        string $bodyText,
        array $bodyExamples,
        string $footerText,
        string $phoneNumber,
        string $phoneButtonText = 'Llamar',
        string $url = '',
        string $urlButtonText = 'Comprar ahora',
        array $urlExamples = [],
        string $language = 'en_US'
    ) {
        $response = Http::withToken($accessToken)
            ->post("https://graph.facebook.com/{$version}/{$wabaId}/message_templates", [
                'name' => $name,
                'language' => $language,
                'category' => 'MARKETING',
                'components' => [
                    [
                        'type' => 'HEADER',
                        'format' => 'IMAGE',
                        'example' => [
                            'header_handle' => [
                                $headerHandle,
                            ],
                        ],
                    ],
                    [
                        'type' => 'BODY',
                        'text' => $bodyText,
                        'example' => [
                            'body_text' => [
                                $bodyExamples,
                            ],
                        ],
                    ],
                    [
                        'type' => 'FOOTER',
                        'text' => $footerText,
                    ],
                    [
                        'type' => 'BUTTONS',
                        'buttons' => [
                            [
                                'type' => 'PHONE_NUMBER',
                                'text' => $phoneButtonText,
                                'phone_number' => $phoneNumber,
                            ],
                            [
                                'type' => 'URL',
                                'text' => $urlButtonText,
                                'url' => $url,
                                'example' => $urlExamples,
                            ],
                        ],
                    ],
                ],
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al crear la plantilla de marketing con imagen y botones de acción.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Crea una plantilla de mensajes de WhatsApp Business de tipo UTILITY
     * con un encabezado de ubicación, variables dinámicas y un botón
     * de respuesta rápida.
     *
     * La plantilla puede incluir los siguientes componentes:
     *
     * - HEADER:
     *   Encabezado de tipo LOCATION.
     *
     *   Este tipo de encabezado permite mostrar una ubicación dentro
     *   del mensaje de WhatsApp.
     *
     * - BODY:
     *   Contiene el mensaje principal y permite utilizar variables
     *   dinámicas mediante marcadores como {{1}}, {{2}}, etc.
     *
     *   El objeto example contiene los valores de ejemplo utilizados
     *   para las variables del cuerpo.
     *
     * - FOOTER:
     *   Texto informativo que se muestra en la parte inferior del mensaje.
     *
     * - BUTTONS:
     *   Permite agregar un botón de respuesta rápida (QUICK_REPLY).
     *
     *   - type: QUICK_REPLY.
     *   - text: Texto que se mostrará en el botón.
     *
     * Este tipo de plantilla puede utilizarse, por ejemplo, para enviar
     * actualizaciones relacionadas con la entrega de un pedido y permitir
     * que el usuario deje de recibir este tipo de notificaciones.
     *
     * Guías oficiales:
     *
     * - Plantillas de mensajes:
     * https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates
     *
     * - Monitoreo de señales de calidad:
     * https://developers.facebook.com/docs/whatsapp/guides/how-to-monitor-quality-signals
     *
     * Referencia del endpoint:
     * https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account/message_templates/
     */
    public function createUtilityLocationTemplate(
        string $version,
        string $accessToken,
        string $wabaId,
        string $name,
        string $bodyText,
        array $bodyExamples,
        string $footerText,
        string $buttonText = 'Dejar de recibir actualizaciones',
        string $language = 'en_US'
    ) {
        $response = Http::withToken($accessToken)
            ->post("https://graph.facebook.com/{$version}/{$wabaId}/message_templates", [
                'name' => $name,
                'language' => $language,
                'category' => 'UTILITY',
                'components' => [
                    [
                        'type' => 'HEADER',
                        'format' => 'LOCATION',
                    ],
                    [
                        'type' => 'BODY',
                        'text' => $bodyText,
                        'example' => [
                            'body_text' => [
                                $bodyExamples,
                            ],
                        ],
                    ],
                    [
                        'type' => 'FOOTER',
                        'text' => $footerText,
                    ],
                    [
                        'type' => 'BUTTONS',
                        'buttons' => [
                            [
                                'type' => 'QUICK_REPLY',
                                'text' => $buttonText,
                            ],
                        ],
                    ],
                ],
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al crear la plantilla UTILITY con ubicación.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Crea una plantilla de mensajes de WhatsApp Business de tipo UTILITY
     * con un encabezado de documento, variables dinámicas y botones de acción.
     *
     * La plantilla puede incluir los siguientes componentes:
     *
     * - HEADER:
     *   Encabezado de tipo DOCUMENT.
     *
     *   Permite utilizar un documento, como un archivo PDF, como encabezado
     *   de la plantilla.
     *
     *   - format: DOCUMENT.
     *   - example: Identificador del documento utilizado como ejemplo
     *     durante la creación y revisión de la plantilla.
     *
     * - BODY:
     *   Contiene el mensaje principal y permite utilizar variables dinámicas
     *   mediante marcadores como {{1}}, {{2}}, etc.
     *
     *   El objeto example contiene los valores de ejemplo utilizados
     *   para las variables del cuerpo.
     *
     * - BUTTONS:
     *   Permite agregar botones para que el usuario pueda realizar acciones.
     *
     *   - PHONE_NUMBER:
     *     Permite agregar un botón para realizar una llamada telefónica.
     *
     *     - text: Texto que se mostrará en el botón.
     *     - phone_number: Número telefónico al que se realizará la llamada.
     *
     *   - URL:
     *     Permite agregar un botón que dirige al usuario a una dirección web.
     *
     *     - text: Texto que se mostrará en el botón.
     *     - url: Dirección web que se abrirá al pulsar el botón.
     *
     * Este tipo de plantilla puede utilizarse, por ejemplo, para enviar
     * confirmaciones de pedidos junto con el recibo o comprobante en PDF
     * y proporcionar opciones para contactar al soporte.
     *
     * Guías oficiales:
     *
     * 1. Plantillas de mensajes:
     * https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates
     *
     * 2. Monitoreo de señales de calidad:
     * https://developers.facebook.com/docs/whatsapp/guides/how-to-monitor-quality-signals
     *
     * Referencia del endpoint:
     * https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account/message_templates/
     */
    public function createUtilityDocumentTemplate(
        string $version,
        string $accessToken,
        string $wabaId,
        string $name,
        string $headerHandle,
        string $bodyText,
        array $bodyExamples,
        array $buttons,
        string $language = 'en_US'
    ) {
        $templateButtons = [];

        foreach ($buttons as $button) {
            if ($button['type'] === 'PHONE_NUMBER') {
                $templateButtons[] = [
                    'type' => 'PHONE_NUMBER',
                    'text' => $button['text'],
                    'phone_number' => $button['phone_number'],
                ];
            }

            if ($button['type'] === 'URL') {
                $templateButtons[] = [
                    'type' => 'URL',
                    'text' => $button['text'],
                    'url' => $button['url'],
                ];
            }
        }

        $response = Http::withToken($accessToken)
            ->post("https://graph.facebook.com/{$version}/{$wabaId}/message_templates", [
                'name' => $name,
                'language' => $language,
                'category' => 'UTILITY',
                'components' => [
                    [
                        'type' => 'HEADER',
                        'format' => 'DOCUMENT',
                        'example' => [
                            'header_handle' => [
                                $headerHandle,
                            ],
                        ],
                    ],
                    [
                        'type' => 'BODY',
                        'text' => $bodyText,
                        'example' => [
                            'body_text' => [
                                $bodyExamples,
                            ],
                        ],
                    ],
                    [
                        'type' => 'BUTTONS',
                        'buttons' => $templateButtons,
                    ],
                ],
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al crear la plantilla UTILITY con documento.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Elimina una plantilla de mensajes de WhatsApp Business.
     *
     * La plantilla se identifica mediante su nombre utilizando el parámetro
     * de consulta "name".
     *
     * Parámetros:
     *
     * - wabaId: ID de la cuenta de WhatsApp Business Account (WABA).
     *
     * - templateName: Nombre exacto de la plantilla que se desea eliminar.
     *
     * La eliminación se realiza mediante una petición DELETE al endpoint
     * "message_templates".
     *
     * Guías oficiales:
     *
     * 1. Plantillas de mensajes:
     * https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates
     *
     * 2. Monitoreo de señales de calidad:
     * https://developers.facebook.com/docs/whatsapp/guides/how-to-monitor-quality-signals
     *
     * Referencia del endpoint:
     * https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account/message_templates/
     */
    public function deleteMessageTemplate(
        string $version,
        string $accessToken,
        string $wabaId,
        string $templateName
    ) {
        $response = Http::withToken($accessToken)
            ->delete("https://graph.facebook.com/{$version}/{$wabaId}/message_templates", [
                'name' => $templateName,
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar la plantilla de WhatsApp.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Elimina una plantilla de mensajes de WhatsApp Business.
     *
     * La plantilla puede identificarse mediante su HSM ID y su nombre.
     *
     * Parámetros:
     *
     * - wabaId: ID de la cuenta de WhatsApp Business Account (WABA).
     *
     * - hsmId: ID de la plantilla (HSM ID) que se desea eliminar.
     *
     * - name: Nombre de la plantilla que se desea eliminar.
     *
     * La eliminación se realiza mediante una petición DELETE al endpoint
     * "message_templates".
     *
     * Los parámetros "hsm_id" y "name" se envían como parámetros de consulta.
     *
     * Guías oficiales:
     *
     * 1. Plantillas de mensajes:
     * https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates
     *
     * 2. Monitoreo de señales de calidad:
     * https://developers.facebook.com/docs/whatsapp/guides/how-to-monitor-quality-signals
     *
     * Referencia del endpoint:
     * https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account/message_templates/
     */
    public function deleteMessageTemplateByHsm(
        string $version,
        string $accessToken,
        string $wabaId,
        string $hsmId,
        string $name
    ) {
        $response = Http::withToken($accessToken)
            ->delete("https://graph.facebook.com/{$version}/{$wabaId}/message_templates", [
                'hsm_id' => $hsmId,
                'name' => $name,
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar la plantilla de WhatsApp.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Crea una plantilla de mensajes de WhatsApp Business.
     *
     * La plantilla puede incluir de forma opcional:
     *
     * - HEADER: Encabezado de la plantilla. Puede ser de tipo TEXT, IMAGE,
     *   VIDEO, DOCUMENT o LOCATION.
     *
     * - BODY: Cuerpo principal de la plantilla. Es obligatorio.
     *   Puede contener variables dinámicas como {{1}}, {{2}}, etc.
     *
     * - FOOTER: Texto opcional que se muestra al final de la plantilla.
     *
     * - BUTTONS: Botones opcionales de tipo QUICK_REPLY, URL,
     *   PHONE_NUMBER, COPY_CODE, OTP, CATALOG, MPM, entre otros.
     *
     * Parámetros:
     *
     * - version: Versión de Graph API, por ejemplo "v24.0".
     *
     * - accessToken: Token de acceso utilizado para autenticarse
     *   contra la API de Meta.
     *
     * - wabaId: ID de la cuenta de WhatsApp Business Account.
     *
     * - name: Nombre único de la plantilla.
     *
     * - language: Idioma de la plantilla, por ejemplo "es" o "en_US".
     *
     * - category: Categoría de la plantilla. Puede ser MARKETING,
     *   UTILITY o AUTHENTICATION.
     *
     * - body: Texto principal de la plantilla.
     *
     * - bodyExamples: Valores de ejemplo para las variables del BODY.
     *   Es opcional y solo debe enviarse cuando el BODY tenga variables.
     *
     * - header: Configuración opcional del HEADER.
     *
     * - footer: Texto opcional del FOOTER.
     *
     * - buttons: Lista opcional de botones.
     *
     * Los componentes HEADER, FOOTER y BUTTONS solamente se agregan
     * al payload cuando son enviados.
     *
     * Guías oficiales:
     *
     * 1. Plantillas de mensajes:
     * https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates
     *
     * 2. Monitoreo de señales de calidad:
     * https://developers.facebook.com/docs/whatsapp/guides/how-to-monitor-quality-signals
     *
     * Referencia del endpoint:
     * https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account/message_templates/
     */
    public function createMessageTemplate(
        string $version,
        string $accessToken,
        string $wabaId,
        string $name,
        string $language,
        string $category,
        string $body,
        array $bodyExamples = [],
        ?array $header = null,
        ?string $footer = null,
        array $buttons = []
    ) {
        $components = [];

        /*
     * BODY
     * El cuerpo es obligatorio.
     */
        $bodyComponent = [
            'type' => 'BODY',
            'text' => $body,
        ];

        /*
     * Agregar ejemplos únicamente cuando existan.
     */
        if (!empty($bodyExamples)) {
            $bodyComponent['example'] = [
                'body_text' => [
                    $bodyExamples,
                ],
            ];
        }

        $components[] = $bodyComponent;

        /*
     * HEADER
     * Se agrega únicamente si fue enviado.
     */
        if (!empty($header)) {
            $components[] = array_merge(
                ['type' => 'HEADER'],
                $header
            );
        }

        /*
     * FOOTER
     * Se agrega únicamente si existe.
     */
        if (!empty($footer)) {
            $components[] = [
                'type' => 'FOOTER',
                'text' => $footer,
            ];
        }

        /*
     * BUTTONS
     * Se agrega únicamente si existen botones.
     */
        if (!empty($buttons)) {
            $components[] = [
                'type' => 'BUTTONS',
                'buttons' => $buttons,
            ];
        }

        /*
     * Payload final.
     */
        $payload = [
            'name' => $name,
            'language' => $language,
            'category' => $category,
            'components' => $components,
        ];

        $response = Http::withToken($accessToken)
            ->post(
                "https://graph.facebook.com/{$version}/{$wabaId}/message_templates",
                $payload
            );

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al crear la plantilla de WhatsApp.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Actualiza una plantilla de mensajes de WhatsApp Business.
     *
     * La plantilla se identifica mediante su TEMPLATE_ID.
     *
     * La plantilla puede incluir:
     *
     * - HEADER: Encabezado opcional. Puede ser de tipo TEXT, IMAGE,
     *   VIDEO, DOCUMENT o LOCATION.
     *
     * - BODY: Cuerpo principal de la plantilla. Es obligatorio.
     *   Puede contener variables dinámicas como {{1}}, {{2}}, etc.
     *
     * - FOOTER: Texto opcional que se muestra al final de la plantilla.
     *
     * - BUTTONS: Botones opcionales de tipo QUICK_REPLY, URL,
     *   PHONE_NUMBER, COPY_CODE, OTP, CATALOG, MPM, entre otros.
     *
     * Parámetros:
     *
     * - version: Versión de Graph API, por ejemplo "v24.0".
     *
     * - accessToken: Token de acceso utilizado para autenticarse
     *   contra la API de Meta.
     *
     * - templateId: ID de la plantilla que se desea actualizar.
     *
     * - name: Nombre de la plantilla.
     *
     * - language: Idioma de la plantilla, por ejemplo "es" o "en_US".
     *
     * - category: Categoría de la plantilla. Puede ser MARKETING,
     *   UTILITY o AUTHENTICATION.
     *
     * - body: Texto principal de la plantilla.
     *
     * - bodyExamples: Valores de ejemplo para las variables del BODY.
     *   Es opcional y solo debe enviarse cuando el BODY tenga variables.
     *
     * - header: Configuración opcional del HEADER.
     *
     * - footer: Texto opcional del FOOTER.
     *
     * - buttons: Lista opcional de botones.
     *
     * Los componentes HEADER, FOOTER y BUTTONS solamente se agregan
     * al payload cuando son enviados.
     *
     * La actualización se realiza mediante una petición POST al
     * endpoint /{TEMPLATE_ID}.
     *
     * Guías oficiales:
     *
     * 1. Plantillas de mensajes:
     * https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates
     *
     * 2. Monitoreo de señales de calidad:
     * https://developers.facebook.com/docs/whatsapp/guides/how-to-monitor-quality-signals
     *
     * Referencia del endpoint:
     * https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account/message_templates/
     */
    public function updateMessageTemplate(
        string $version,
        string $accessToken,
        string $templateId,
        string $name,
        string $language,
        string $category,
        string $body,
        array $bodyExamples = [],
        ?array $header = null,
        ?string $footer = null,
        array $buttons = []
    ) {
        $components = [];

        /*
     * BODY
     */
        $bodyComponent = [
            'type' => 'BODY',
            'text' => $body,
        ];

        /*
     * Agregar ejemplos únicamente cuando existan.
     */
        if (!empty($bodyExamples)) {
            $bodyComponent['example'] = [
                'body_text' => [
                    $bodyExamples,
                ],
            ];
        }

        $components[] = $bodyComponent;

        /*
     * HEADER
     * Se agrega únicamente si fue enviado.
     */
        if (!empty($header)) {
            $components[] = array_merge(
                ['type' => 'HEADER'],
                $header
            );
        }

        /*
     * FOOTER
     * Se agrega únicamente si existe.
     */
        if (!empty($footer)) {
            $components[] = [
                'type' => 'FOOTER',
                'text' => $footer,
            ];
        }

        /*
     * BUTTONS
     * Se agrega únicamente si existen botones.
     */
        if (!empty($buttons)) {
            $components[] = [
                'type' => 'BUTTONS',
                'buttons' => $buttons,
            ];
        }

        /*
     * Payload final.
     */
        $payload = [
            'name' => $name,
            'language' => $language,
            'category' => $category,
            'components' => $components,
        ];

        $response = Http::withToken($accessToken)
            ->post(
                "https://graph.facebook.com/{$version}/{$templateId}",
                $payload
            );

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar la plantilla de WhatsApp.',
            'error' => $response->json(),
        ], $response->status());
    }
}
