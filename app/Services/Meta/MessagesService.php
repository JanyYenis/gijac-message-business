<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;

class MessagesService
{
    /**
     * Send Text Message
     *
     * Descripción original:
     * To send a text message, make a POST call to /{{{Phone-Number-ID}}/messages
     * and attach a message object with type = "text". Then, add a text object.
     *
     * For more information about the "Text Object" structure, see:
     * Text object:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#text-object
     *
     * Response:
     * A successful response includes an object with an identifier prefixed
     * with "wamid". Use the ID listed after "wamid" to track your message status.
     *
     * Descripción en español:
     * Envía un mensaje de texto a un destinatario mediante WhatsApp Cloud API.
     * El mensaje puede contener texto y enlaces. El parámetro preview_url permite
     * habilitar o deshabilitar la vista previa de los enlaces.
     *
     * Método HTTP: POST
     *
     * Endpoint:
     * /{phoneNumberId}/messages
     *
     * Parámetros:
     * @param string $accessToken Token de acceso válido de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número de teléfono del destinatario,
     *        incluido el código de país.
     * @param string $message Contenido del mensaje de texto.
     * @param bool $previewUrl Indica si se debe mostrar una vista previa
     *        de los enlaces incluidos en el mensaje. Por defecto, false.
     *
     * Consideraciones:
     * - El destinatario debe enviarse en el formato aceptado por WhatsApp.
     * - Para mensajes iniciados por la empresa fuera de la ventana de atención
     *   al cliente, generalmente se requiere utilizar una plantilla aprobada.
     * - La respuesta exitosa incluye un identificador de mensaje que comienza
     *   con "wamid". Este identificador permite hacer seguimiento al estado
     *   del mensaje mediante los mecanismos de estado de WhatsApp.
     *
     * @return array|mixed Respuesta JSON de Meta o respuesta HTTP de error.
     */
    public function sendTextMessage(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $message,
        bool $previewUrl = false
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $recipientPhoneNumber,
                'type' => 'text',
                'text' => [
                    'preview_url' => $previewUrl,
                    'body' => $message,
                ],
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible enviar el mensaje de texto por WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }

    /**
     * Send Reply to Text Message
     *
     * Descripción original:
     * To send a reply-to text message, make a POST call to
     * /{{{Phone-Number-ID}}/messages and use the context.message_id field.
     *
     * Context field is used for specifying to which previous message
     * (context.message_id) you are replying to.
     *
     * Request Parameters:
     * - messaging_product: Required. Messaging service used for the request.
     *   Use "whatsapp".
     * - context.message_id: Required for context object. Specifies the ID
     *   of the message being replied to.
     *
     * For more information about the "Text Object" structure, see:
     * Text object:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#text-object
     *
     * Response:
     * A successful response includes an object with an identifier prefixed
     * with "wamid". Use the ID listed after "wamid" to track your message status.
     *
     * Descripción en español:
     * Envía un mensaje de texto como respuesta a un mensaje anterior.
     * El campo context.message_id contiene el identificador del mensaje original
     * al que se desea responder.
     *
     * Método HTTP: POST
     *
     * Endpoint:
     * /{phoneNumberId}/messages
     *
     * Parámetros:
     * @param string $accessToken Token de acceso válido de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número de teléfono del destinatario,
     *        incluido el código de país.
     * @param string $previousMessageId ID del mensaje original al que se responde.
     * @param string $message Texto de la respuesta.
     * @param bool $previewUrl Indica si se muestra una vista previa de los enlaces.
     *        Por defecto, false.
     *
     * Consideraciones:
     * - messaging_product debe ser "whatsapp".
     * - context.message_id identifica el mensaje original al que se responde.
     * - El destinatario debe corresponder al contexto de la conversación.
     * - La respuesta exitosa contiene un identificador wamid que puede utilizarse
     *   para realizar el seguimiento del mensaje enviado.
     * - Fuera de la ventana de atención al cliente, pueden aplicarse las reglas
     *   de WhatsApp para el envío de plantillas aprobadas.
     *
     * @return array|mixed Respuesta JSON de Meta o respuesta HTTP de error.
     */
    public function sendReplyToTextMessage(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $previousMessageId,
        string $message,
        bool $previewUrl = false
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $recipientPhoneNumber,
                'context' => [
                    'message_id' => $previousMessageId,
                ],
                'type' => 'text',
                'text' => [
                    'preview_url' => $previewUrl,
                    'body' => $message,
                ],
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible responder al mensaje de WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }

    /**
     * Send Text Message with Preview URL
     *
     * Comentario original:
     * No hay comentario en la documentación.
     *
     * Descripción:
     * Envía un mensaje de texto por WhatsApp Cloud API con la vista previa
     * de los enlaces habilitada. Cuando el contenido incluye una URL compatible,
     * WhatsApp puede mostrar una vista previa del enlace en la conversación.
     *
     * Método HTTP: POST
     *
     * Endpoint:
     * /{phoneNumberId}/messages
     *
     * Parámetros:
     * @param string $accessToken Token de acceso válido de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número de teléfono del destinatario,
     *        incluido el código de país.
     * @param string $message Texto que se enviará, incluyendo la URL.
     *
     * Consideraciones:
     * - El campo text.preview_url se establece en true para habilitar la
     *   vista previa de los enlaces.
     * - La vista previa depende de que WhatsApp pueda procesar el enlace.
     * - El destinatario debe estar en un formato de teléfono aceptado por WhatsApp.
     * - Una respuesta exitosa contiene un identificador de mensaje con prefijo
     *   "wamid", que puede utilizarse para hacer seguimiento del envío.
     *
     * @return array|mixed Respuesta JSON de Meta o respuesta HTTP de error.
     */
    public function sendTextMessageWithPreviewUrl(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $message
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'to' => $recipientPhoneNumber,
                'type' => 'text',
                'text' => [
                    'preview_url' => true,
                    'body' => $message,
                ],
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible enviar el mensaje de WhatsApp con vista previa.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }

    /**
     * Send Reply with Reaction Message
     *
     * Comentario original:
     * To send reaction messages, make a POST call to /PHONE_NUMBER_ID/messages
     * and attach a message object with type = reaction. Then, add a reaction object.
     *
     * Parameters:
     *
     * - messaging_product:
     *   Required. Always set this value to "whatsapp".
     *
     * - recipient_type:
     *   Optional. Currently, you can only send messages to individuals.
     *   Set this value to "individual". Default: individual.
     *
     * - to:
     *   Required. WhatsApp ID or phone number for the person you want to send
     *   a message to.
     *
     *   Phone Numbers, Formatting:
     *   https://developers.facebook.com/docs/whatsapp/cloud-api/reference/phone-numbers#formatting
     *
     * - type:
     *   Required. To send a reply with a reaction, set this string value
     *   to "reaction".
     *
     * - reaction:
     *   Required. The reaction object you are using for your reply.
     *
     *   Reaction Object:
     *   https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#reaction-object
     *
     * Consideraciones originales:
     * If the previous message is more than 30 days old or doesn't correspond
     * to any message in the conversation, the reaction message will not be
     * delivered and you will receive a Webhook with the code 131009.
     *
     * Descripción en español:
     * Envía una reacción con un emoji a un mensaje específico de WhatsApp.
     * El identificador del mensaje original se proporciona mediante
     * reaction.message_id.
     *
     * Método HTTP: POST
     *
     * Endpoint:
     * /{phoneNumberId}/messages
     *
     * Parámetros:
     * @param string $accessToken Token de acceso válido de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número de WhatsApp del destinatario.
     * @param string $messageId ID del mensaje original al que se reaccionará.
     * @param string $emoji Emoji de la reacción, por ejemplo "👍" o "❤️".
     *
     * @return array|mixed Respuesta JSON de Meta o respuesta HTTP de error.
     */
    public function sendReplyWithReactionMessage(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $messageId,
        string $emoji
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        if (trim($messageId) === '' || trim($emoji) === '') {
            return response()->json([
                'success' => false,
                'message' => 'El ID del mensaje y el emoji son obligatorios.',
            ], 422);
        }

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $recipientPhoneNumber,
                'type' => 'reaction',
                'reaction' => [
                    'message_id' => $messageId,
                    'emoji' => $emoji,
                ],
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible enviar la reacción al mensaje de WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }

    /**
     * Send Image Message by ID
     *
     * Comentario original:
     * To send a reply to an image message, make a POST call to
     * /{{Phone-Number-ID}}/messages and attach a message object with
     * type = image. Then add a corresponding image object.
     *
     * Send an image message to your customers using link or media ID
     * uploaded from media endpoint.
     *
     * Response:
     * A successful response includes an object with an identifier prefixed
     * with "wamid". Use the ID listed after "wamid" to track your message status.
     *
     * Descripción en español:
     * Envía una imagen a un destinatario de WhatsApp utilizando el ID de un
     * archivo multimedia previamente cargado en Meta.
     *
     * Método HTTP: POST
     *
     * Endpoint:
     * /{phoneNumberId}/messages
     *
     * Parámetros:
     * @param string $accessToken Token de acceso válido de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número de teléfono del destinatario,
     *        incluido el código de país.
     * @param string $imageId Identificador de la imagen cargada previamente
     *        mediante el endpoint de medios de WhatsApp.
     * @param string|null $caption Texto opcional que acompaña a la imagen.
     *
     * Consideraciones:
     * - El objeto image utiliza el campo id para identificar el archivo multimedia.
     * - La imagen debe haberse cargado previamente y estar disponible para
     *   el número de WhatsApp Business emisor.
     * - También es posible enviar una imagen mediante una URL pública compatible,
     *   utilizando el campo link en lugar de id.
     * - La respuesta exitosa incluye un identificador wamid para realizar
     *   seguimiento del estado del mensaje.
     *
     * @return array|mixed Respuesta JSON de Meta o respuesta HTTP de error.
     *
     * Referencias:
     * En el comentario original no se proporcionó un enlace directo a la
     * documentación de este endpoint.
     */
    public function sendImageMessageById(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $imageId,
        ?string $caption = null
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        $image = [
            'id' => $imageId,
        ];

        if ($caption !== null && trim($caption) !== '') {
            $image['caption'] = $caption;
        }

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $recipientPhoneNumber,
                'type' => 'image',
                'image' => $image,
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible enviar la imagen por WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }

    /**
     * Send Reply to Image Message by ID
     *
     * Comentario original:
     * To send a reply-to image message, make a POST call to
     * /{{Phone-Number-ID}}/messages and attach a message object with
     * type = image. Add the image.id of the image.
     *
     * Assign context.message_id the message id of the message you want to reply to.
     *
     * Request Parameters:
     *
     * - messaging_product:
     *   Required. Messaging service used for the request. Use "whatsapp".
     *
     * - context.message_id:
     *   Required for context object. Specifies the ID of the message being replied to.
     *
     * - type:
     *   Required. Specifies the media type. Use "image".
     *
     * - image.id:
     *   Specifies the ID of the image you are replying to.
     *
     * For more information about the Media Object structure, see:
     * Media Object:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
     *
     * Response:
     * A successful response includes an object with an identifier prefixed
     * with "wamid". Use the ID listed after "wamid" to track your message status.
     *
     * Descripción en español:
     * Envía una imagen como respuesta a un mensaje anterior de WhatsApp.
     * El campo context.message_id relaciona la respuesta con el mensaje original,
     * mientras que image.id identifica la imagen que se enviará.
     *
     * Método HTTP: POST
     *
     * Endpoint:
     * /{phoneNumberId}/messages
     *
     * Parámetros:
     * @param string $accessToken Token de acceso válido de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número de teléfono del destinatario,
     *        incluido el código de país.
     * @param string $previousMessageId ID del mensaje original al que se responde.
     * @param string $imageId ID de la imagen cargada previamente en Meta.
     * @param string|null $caption Texto opcional que acompaña a la imagen.
     *
     * Consideraciones:
     * - messaging_product debe ser "whatsapp".
     * - context.message_id debe contener el ID real del mensaje original.
     * - image.id debe identificar un archivo multimedia disponible para el
     *   número de WhatsApp Business emisor.
     * - La respuesta exitosa contiene un identificador wamid que puede utilizarse
     *   para hacer seguimiento del mensaje enviado.
     *
     * @return array|mixed Respuesta JSON de Meta o respuesta HTTP de error.
     */
    public function sendReplyToImageMessageById(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $previousMessageId,
        string $imageId,
        ?string $caption = null
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        if (trim($previousMessageId) === '' || trim($imageId) === '') {
            return response()->json([
                'success' => false,
                'message' => 'El ID del mensaje original y el ID de la imagen son obligatorios.',
            ], 422);
        }

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        $image = [
            'id' => $imageId,
        ];

        if ($caption !== null && trim($caption) !== '') {
            $image['caption'] = $caption;
        }

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $recipientPhoneNumber,
                'context' => [
                    'message_id' => $previousMessageId,
                ],
                'type' => 'image',
                'image' => $image,
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible responder con una imagen por WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }

    /**
     * Send Image Message by URL.
     *
     * Envía un mensaje de imagen por WhatsApp utilizando una URL pública de la imagen.
     *
     * Comentario original:
     * To send a media message, make a POST call to /{{Phone-Number-ID}}/messages
     * and attach a message object with type = image. Then, be sure to include
     * the link to the image.
     *
     * Send an audio message to your customers using a link to an image file.
     *
     * For more information about the Media Object structure, see:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
     *
     * Endpoint:
     * POST /{Phone-Number-ID}/messages
     *
     * Parámetros:
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId Identificador del número de teléfono de WhatsApp Business.
     * @param string $recipientPhoneNumber Número de WhatsApp del destinatario, con código de país.
     * @param string $imageUrl URL pública HTTPS de la imagen.
     * @param string|null $caption Texto opcional que acompaña a la imagen.
     *
     * @return array|\Illuminate\Http\JsonResponse Respuesta de la API de WhatsApp.
     */
    public function sendImageMessageByUrl(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $imageUrl,
        ?string $caption = null
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        $image = [
            'link' => $imageUrl,
        ];

        if ($caption !== null && trim($caption) !== '') {
            $image['caption'] = $caption;
        }

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $recipientPhoneNumber,
                'type' => 'image',
                'image' => $image,
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar la imagen por WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }

    /**
     * Send Reply to Image Message by URL.
     *
     * Envía una imagen mediante una URL como respuesta a un mensaje anterior
     * de WhatsApp.
     *
     * Comentario original:
     * To send an image link as a reply to message, make a POST call to
     * /{{Phone-Number-ID}}/messages and attach a media object with type = image.
     * Then, be sure to include the link to the image.
     *
     * Assign context.message_id the message ID of the message you want to reply to.
     *
     * Request Parameters:
     * - messaging_product: Required. Messaging service used for the request. Use "whatsapp".
     * - context.message_id: Required for context object. Specifies the ID of the message being replied to.
     * - type: Required. Specifies the media type. Use "image".
     * - image.link: Specifies the link of the image you are using for your reply.
     *
     * For more information about the Media Object structure, see:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with
     * wamid. Use the ID listed after wamid to track your message status.
     *
     * Endpoint:
     * POST /{Phone-Number-ID}/messages
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId Identificador del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número de WhatsApp del destinatario con código de país.
     * @param string $messageId ID del mensaje anterior al que se responderá.
     * @param string $imageUrl URL pública de la imagen que se enviará.
     * @param string|null $caption Texto opcional que acompaña a la imagen.
     *
     * @return array|\Illuminate\Http\JsonResponse Respuesta de la API de WhatsApp.
     */
    public function sendReplyToImageMessageByUrl(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $messageId,
        string $imageUrl,
        ?string $caption = null
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        $image = [
            'link' => $imageUrl,
        ];

        if ($caption !== null && trim($caption) !== '') {
            $image['caption'] = $caption;
        }

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $recipientPhoneNumber,
                'context' => [
                    'message_id' => $messageId,
                ],
                'type' => 'image',
                'image' => $image,
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al responder con una imagen por WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }

    /**
     * Send Audio Message by ID.
     *
     * Envía un mensaje de audio por WhatsApp utilizando el ID de un archivo
     * multimedia previamente cargado mediante el endpoint de Media de Meta.
     *
     * Comentario original:
     * To send a media message, make a POST call to /{{Phone-Number-ID}}/messages
     * and attach a message object with type = audio. Then, add the corresponding
     * audio object ID.
     *
     * For more information about the Media Object structure, see:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
     *
     * Send an audio message to your customers using the media ID uploaded from media endpoint.
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with
     * wamid. Use the ID listed after wamid to track your message status.
     *
     * Endpoint:
     * POST /{Phone-Number-ID}/messages
     *
     * Parámetros:
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId Identificador del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número de WhatsApp del destinatario con código de país.
     * @param string $audioId Identificador del archivo de audio previamente cargado en Meta.
     *
     * @return array|\Illuminate\Http\JsonResponse Respuesta de la API de WhatsApp.
     */
    public function sendAudioMessageById(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $audioId
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $recipientPhoneNumber,
                'type' => 'audio',
                'audio' => [
                    'id' => $audioId,
                ],
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el audio por WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }

    /**
     * Send Reply to Audio Message by ID.
     *
     * Envía un mensaje de audio por WhatsApp como respuesta a un mensaje anterior,
     * utilizando el ID de un archivo multimedia previamente cargado en Meta.
     *
     * Comentario original:
     * To send a media message, make a POST call to /{{Phone-Number-ID}}/messages
     * and attach a media object with type = audio. Then, add the corresponding
     * audio object ID.
     *
     * Assign context.message_id the message id of the message you want to reply to.
     *
     * Request Parameters:
     * - messaging_product: Required. Messaging service used for the request. Use "whatsapp".
     * - context.message_id: Required for context object. Specifies the ID of the message being replied to.
     * - type: Required. Specifies the media type. Use "audio".
     * - audio.id: Specifies the ID of the media object you are replying to.
     *
     * For more information about the Media Object structure, see:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with
     * wamid. Use the ID listed after wamid to track your message status.
     *
     * Endpoint:
     * POST /{Phone-Number-ID}/messages
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId Identificador del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número de WhatsApp del destinatario con código de país.
     * @param string $messageId Identificador del mensaje anterior al que se responderá.
     * @param string $audioId Identificador del archivo de audio previamente cargado en Meta.
     *
     * @return array|\Illuminate\Http\JsonResponse Respuesta de la API de WhatsApp.
     */
    public function sendReplyToAudioMessageById(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $messageId,
        string $audioId
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $recipientPhoneNumber,
                'context' => [
                    'message_id' => $messageId,
                ],
                'type' => 'audio',
                'audio' => [
                    'id' => $audioId,
                ],
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al responder con un audio por WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }

    /**
     * Send Audio Message by URL.
     *
     * Envía un mensaje de audio por WhatsApp utilizando una URL accesible del archivo.
     *
     * Comentario original:
     * To send a media message, make a POST call to /{{Phone-Number-ID}}/messages
     * and attach a message object with type = audio. Be sure to include the link
     * to the audio.
     *
     * For more information about the Media Object structure, see:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
     *
     * Send an audio message to your customers using link from media endpoint.
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with
     * wamid. Use the ID listed after wamid to track your message status.
     *
     * Endpoint:
     * POST /{Phone-Number-ID}/messages
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId Identificador del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número de WhatsApp del destinatario con código de país.
     * @param string $audioUrl URL accesible del archivo de audio.
     *
     * @return array|\Illuminate\Http\JsonResponse Respuesta de la API de WhatsApp.
     */
    public function sendAudioMessageByUrl(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $audioUrl
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $recipientPhoneNumber,
                'type' => 'audio',
                'audio' => [
                    'link' => $audioUrl,
                ],
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al enviar el audio por WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }

    /**
     * Send Reply to Audio Message by URL.
     *
     * Envía un audio por WhatsApp mediante una URL como respuesta a un mensaje anterior.
     *
     * Comentario original:
     * To send an audio link as a reply to message, make a POST call to
     * /{{Phone-Number-ID}}/messages and attach a media object with type = image.
     * Then, be sure to include the link to the audio.
     *
     * Assign context.message_id the message ID of the message you want to reply to.
     *
     * Request Parameters:
     * - messaging_product: Required. Messaging service used for the request. Use "whatsapp".
     * - context.message_id: Required for context object. Specifies the ID of the message you are replying to.
     * - type: Required. Specifies the media type. Use "audio".
     * - audio.link: Specifies the link of the image you are using for your reply.
     *
     * For more information about the Media Object structure, see:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with
     * wamid. Use the ID listed after wamid to track your message status.
     *
     * Endpoint:
     * POST /{Phone-Number-ID}/messages
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId Identificador del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número del destinatario con código de país.
     * @param string $messageId Identificador del mensaje al que se responderá.
     * @param string $audioUrl URL pública del archivo de audio.
     *
     * @return array|\Illuminate\Http\JsonResponse Respuesta de la API de WhatsApp.
     */
    public function sendReplyToAudioMessageByUrl(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $messageId,
        string $audioUrl
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $recipientPhoneNumber,
                'context' => [
                    'message_id' => $messageId,
                ],
                'type' => 'audio',
                'audio' => [
                    'link' => $audioUrl,
                ],
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al responder con un audio por WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }

    /**
     * Send Document Message by ID
     *
     * To send a media message, make a POST call to /{{Phone-Number-ID}}/messages
     * and attach a message object with type = document. Then, add the corresponding
     * document object ID.
     *
     * For more information about the Media Object structure, see:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
     *
     * Send a document message to your customers using media ID uploaded from media endpoint.
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with wamid.
     * Use the ID listed after wamid to track your message status.
     *
     * Endpoint:
     * POST /{Phone-Number-ID}/messages
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp Business.
     * @param string $recipientPhoneNumber Número del destinatario, con código de país y sin +.
     * @param string $documentId ID del documento previamente cargado en Meta.
     * @param string|null $caption Texto descriptivo opcional del documento.
     * @param string|null $filename Nombre de archivo opcional que se mostrará al destinatario.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendDocumentMessageById(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $documentId,
        ?string $caption = null,
        ?string $filename = null
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $document = [
            'id' => $documentId,
        ];

        if ($caption !== null && $caption !== '') {
            $document['caption'] = $caption;
        }

        if ($filename !== null && $filename !== '') {
            $document['filename'] = $filename;
        }

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $recipientPhoneNumber,
                'type' => 'document',
                'document' => $document,
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible enviar el documento por WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Send Reply to Document Message by ID
     *
     * To send a reply to document message, make a POST call to
     * /{{Phone-Number-ID}}/messages and attach a media object with type = document.
     * Then, add the corresponding document object ID.
     *
     * Assign context.message_id the message id of the message you want to reply to.
     *
     * Request Parameters:
     * - messaging_product: Required. Messaging service used for the request. Use "whatsapp".
     * - context.message_id: Required for context object. Specifies the ID of the message being replied to.
     * - type: Required. Specifies the media type. Use "document".
     * - document.id: Specifies the ID of the document object you are replying to.
     *
     * For more information about the Media Object structure, see:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
     *
     * Send a document message to your customers using media ID uploaded from media endpoint.
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with wamid.
     * Use the ID listed after wamid to track your message status.
     *
     * Endpoint:
     * POST /{Phone-Number-ID}/messages
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número del destinatario con código de país, sin +.
     * @param string $messageId ID del mensaje anterior al que se responderá.
     * @param string $documentId ID del documento previamente cargado en Meta.
     * @param string|null $caption Texto descriptivo opcional del documento.
     * @param string|null $filename Nombre opcional del archivo que verá el destinatario.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendReplyToDocumentMessageById(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $messageId,
        string $documentId,
        ?string $caption = null,
        ?string $filename = null
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $document = [
            'id' => $documentId,
        ];

        if ($caption !== null && $caption !== '') {
            $document['caption'] = $caption;
        }

        if ($filename !== null && $filename !== '') {
            $document['filename'] = $filename;
        }

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $recipientPhoneNumber,
                'context' => [
                    'message_id' => $messageId,
                ],
                'type' => 'document',
                'document' => $document,
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible responder con el documento por WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Send Document Message by URL
     *
     * To send a media message, make a POST call to /{{Phone-Number-ID}}/messages
     * and attach a message object with type = document. Then, include the link to the document.
     *
     * For more information about the Media Object structure, see:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
     *
     * Send a document message to your customers using link.
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with wamid.
     * Use the ID listed after wamid to track your message status.
     *
     * Endpoint:
     * POST /{Phone-Number-ID}/messages
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número del destinatario con código de país, sin +.
     * @param string $documentUrl URL del documento que se enviará.
     * @param string|null $caption Texto descriptivo opcional del documento.
     * @param string|null $filename Nombre opcional del archivo.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendDocumentMessageByUrl(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $documentUrl,
        ?string $caption = null,
        ?string $filename = null
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $document = [
            'link' => $documentUrl,
        ];

        if ($caption !== null && $caption !== '') {
            $document['caption'] = $caption;
        }

        if ($filename !== null && $filename !== '') {
            $document['filename'] = $filename;
        }

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $recipientPhoneNumber,
                'type' => 'document',
                'document' => $document,
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible enviar el documento por WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Send Reply to Document Message by URL
     *
     * To send a document link as a reply to message, make a POST call to
     * /{{Phone-Number-ID}}/messages and attach a media object with type = document.
     * Then, be sure to include the link to the document.
     *
     * Assign context.message_id the message ID of the message you want to reply to.
     *
     * Request Parameters:
     * - messaging_product: Required. Messaging service used for the request. Use "whatsapp".
     * - context.message_id: Required for context object. Specifies the ID of the message you are replying to.
     * - type: Required. Specifies the media type. Use "document".
     * - document.link: Specifies the link of the document you are sending for your reply.
     *
     * For more information about the Media Object structure, see:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with wamid.
     * Use the ID listed after wamid to track your message status.
     *
     * Endpoint:
     * POST /{Phone-Number-ID}/messages
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número del destinatario con código de país, sin +.
     * @param string $messageId ID del mensaje al que se responderá.
     * @param string $documentUrl URL del documento que se enviará.
     * @param string|null $caption Texto descriptivo opcional del documento.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendReplyToDocumentMessageByUrl(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $messageId,
        string $documentUrl,
        ?string $caption = null
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $document = [
            'link' => $documentUrl,
        ];

        if ($caption !== null && $caption !== '') {
            $document['caption'] = $caption;
        }

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $recipientPhoneNumber,
                'context' => [
                    'message_id' => $messageId,
                ],
                'type' => 'document',
                'document' => $document,
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible responder con el documento por WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Send Sticker Message by ID
     *
     * To send a media message, make a POST call to /{{Phone-Number-ID}}/messages
     * and attach a message object with type = sticker. Then, add the corresponding
     * sticker object ID.
     *
     * For more information about the Media Object structure, see:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
     *
     * Send a sticker message to your customers using the media ID uploaded from media endpoint.
     *
     * Request Parameters:
     * - messaging_product: Required. Messaging service used for the request. Always use "whatsapp".
     * - type: Required. Specifies the media type. Use "sticker".
     * - sticker.id: Specifies the ID of the sticker object you are sending.
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with wamid.
     * Use the ID listed after wamid to track your message status.
     *
     * Endpoint:
     * POST /{Phone-Number-ID}/messages
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número del destinatario con código de país, sin +.
     * @param string $stickerId ID del sticker previamente cargado en Meta.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendStickerMessageById(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $stickerId
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $recipientPhoneNumber,
                'type' => 'sticker',
                'sticker' => [
                    'id' => $stickerId,
                ],
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible enviar el sticker por WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Send Reply to Sticker Message by ID
     *
     * To send a reply to sticker message, make a POST call to
     * /{{Phone-Number-ID}}/messages and attach a media object with type = sticker.
     * Then, add the corresponding sticker object ID.
     *
     * Assign context.message_id the message id of the message you want to reply to.
     *
     * Request Parameters:
     * - messaging_product: Required. Messaging service used for the request. Use "whatsapp".
     * - context.message_id: Required for context object. Specifies the ID of the message you are replying to.
     * - type: Required. Specifies the media type. Use "sticker".
     * - sticker.id: Specifies the ID of the sticker object you are sending.
     *
     * For more information about the Media Object structure, see:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with wamid.
     * Use the ID listed after wamid to track your message status.
     *
     * Endpoint:
     * POST /{Phone-Number-ID}/messages
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número del destinatario con código de país, sin +.
     * @param string $messageId ID del mensaje anterior al que se responderá.
     * @param string $stickerId ID del sticker previamente cargado en Meta.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendReplyToStickerMessageById(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $messageId,
        string $stickerId
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $recipientPhoneNumber,
                'context' => [
                    'message_id' => $messageId,
                ],
                'type' => 'sticker',
                'sticker' => [
                    'id' => $stickerId,
                ],
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible responder con el sticker por WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Send Sticker Message by URL
     *
     * To send a media message, make a POST call to /{{Phone-Number-ID}}/messages
     * and attach a message object with type = sticker. Then, add a link to the
     * corresponding sticker file.
     *
     * For more information about the Media Object structure, see:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
     *
     * Send a sticker message to your customers using link.
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with wamid.
     * Use the ID listed after wamid to track your message status.
     *
     * Endpoint:
     * POST /{Phone-Number-ID}/messages
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número del destinatario con código de país, sin +.
     * @param string $stickerUrl URL del archivo del sticker.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendStickerMessageByUrl(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $stickerUrl
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $recipientPhoneNumber,
                'type' => 'sticker',
                'sticker' => [
                    'link' => $stickerUrl,
                ],
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible enviar el sticker por WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Send Reply to Sticker Message by URL
     *
     * To send a sticker link as a reply to message, make a POST call to
     * /{{Phone-Number-ID}}/messages and attach a media object with type = sticker.
     * Then, be sure to include the link to the sticker.
     *
     * Assign context.message_id the message ID of the message you want to reply to.
     *
     * Request Parameters:
     * - messaging_product: Required. Messaging service used for the request. Use "whatsapp".
     * - context.message_id: Required for context object. Specifies the ID of the message you are replying to.
     * - type: Required. Specifies the media type. Use "sticker".
     * - sticker.link: Specifies the link of the sticker you are sending for your reply.
     *
     * For more information about the Media Object structure, see:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with wamid.
     * Use the ID listed after wamid to track your message status.
     *
     * Endpoint:
     * POST /{Phone-Number-ID}/messages
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número del destinatario con código de país, sin +.
     * @param string $messageId ID del mensaje al que se responderá.
     * @param string $stickerUrl URL del archivo del sticker.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendReplyToStickerMessageByUrl(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $messageId,
        string $stickerUrl
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $recipientPhoneNumber,
                'context' => [
                    'message_id' => $messageId,
                ],
                'type' => 'sticker',
                'sticker' => [
                    'link' => $stickerUrl,
                ],
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible responder con el sticker por WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Send Video Message by ID
     *
     * To send a media message, make a POST call to /{{Phone-Number-ID}}/messages
     * and attach a message object with type = video. Then, add a corresponding
     * video object ID.
     *
     * For more information about the Media Object structure, see:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
     *
     * Send a video message to your customers using the media ID uploaded from media endpoint.
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with wamid.
     * Use the ID listed after wamid to track your message status.
     *
     * Endpoint:
     * POST /{Phone-Number-ID}/messages
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número del destinatario con código de país, sin +.
     * @param string $videoId ID del video previamente cargado en Meta.
     * @param string|null $caption Texto descriptivo opcional que acompaña al video.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendVideoMessageById(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $videoId,
        ?string $caption = null
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $video = [
            'id' => $videoId,
        ];

        if ($caption !== null && $caption !== '') {
            $video['caption'] = $caption;
        }

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'recipient_type' => 'individual',
                'to' => $recipientPhoneNumber,
                'type' => 'video',
                'video' => $video,
            ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible enviar el video por WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Send Reply to Video Message by ID
     *
     * To send a reply to video message, make a POST call to /{{Phone-Number-ID}}/messages
     * and attach a media object with type = "video". Then, add the corresponding video object ID.
     *
     * Assign context.message_id the message id of the message you want to reply to.
     *
     * Request Parameters:
     * - messaging_product: Required. Messaging service used for the request. Use "whatsapp".
     * - context.message_id: Required for context object. Specifies the ID of the message you are replying to.
     * - type: Required. Specifies the media type. Use "video".
     * - video.id: Specifies the ID of the video object you are sending.
     *
     * For more information about the Media Object structure, see:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
     *
     * Sends a video message as a reply to using the media ID uploaded from the /media endpoint.
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with "wamid".
     * Use the ID listed after "wamid" to track your message status.
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp Business.
     * @param string $recipientPhoneNumber Número del destinatario con código de país.
     * @param string $messageId ID del mensaje anterior al que se responderá.
     * @param string $videoId ID del video previamente cargado en Meta.
     * @param string|null $caption Texto opcional para acompañar el video.
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendReplyToVideoMessageById(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $messageId,
        string $videoId,
        ?string $caption = null
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipientPhoneNumber,
            'context' => [
                'message_id' => $messageId,
            ],
            'type' => 'video',
            'video' => [
                'id' => $videoId,
            ],
        ];

        if ($caption !== null && trim($caption) !== '') {
            $payload['video']['caption'] = $caption;
        }

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post(
                "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages",
                $payload
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible enviar el video como respuesta al mensaje.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Send Video Message by URL
     *
     * To send a media message, make a POST call to /{{Phone-Number-ID}}/messages
     * and attach a message object with type = video. Then, add a link to the video.
     *
     * For more information about the Media Object structure, see:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
     *
     * Send a video message to your customers using a link.
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with "wamid".
     * Use the ID listed after "wamid" to track your message status.
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp Business.
     * @param string $recipientPhoneNumber Número del destinatario con código de país.
     * @param string $videoUrl URL del video que se enviará.
     * @param string|null $caption Texto opcional que acompañará al video.
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendVideoMessageByUrl(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $videoUrl,
        ?string $caption = null
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipientPhoneNumber,
            'type' => 'video',
            'video' => [
                'link' => $videoUrl,
            ],
        ];

        if ($caption !== null && trim($caption) !== '') {
            $payload['video']['caption'] = $caption;
        }

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post(
                "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages",
                $payload
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible enviar el video por URL.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Send Reply to Video Message by URL
     *
     * To send a video link as a reply to message, make a POST call to
     * /{{Phone-Number-ID}}/messages and attach a media object with type = video.
     * Then, be sure to include the link to the video.
     *
     * Assign context.message_id the message ID of the message you want to reply to.
     *
     * Request Parameters:
     * - messaging_product: Required. Messaging service used for the request. Use "whatsapp".
     * - context.message_id: Required for context object. Specifies the ID of the message you are replying to.
     * - type: Required. Specifies the media type. Use "video".
     * - video.link: Specifies the link to the video you are sending for your reply.
     *
     * For more information about the Media Object structure, see:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#media-object
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with "wamid".
     * Use the ID listed after "wamid" to track your message status.
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp Business.
     * @param string $recipientPhoneNumber Número del destinatario con código de país.
     * @param string $messageId ID del mensaje al que se responderá.
     * @param string $videoUrl URL del video que se enviará.
     * @param string|null $caption Texto opcional que acompañará al video.
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendReplyToVideoMessageByUrl(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $messageId,
        string $videoUrl,
        ?string $caption = null
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipientPhoneNumber,
            'context' => [
                'message_id' => $messageId,
            ],
            'type' => 'video',
            'video' => [
                'link' => $videoUrl,
            ],
        ];

        if ($caption !== null && trim($caption) !== '') {
            $payload['video']['caption'] = $caption;
        }

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post(
                "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages",
                $payload
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible enviar el video como respuesta al mensaje.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Send Contact Message
     *
     * To send contact messages, make a POST call to /{{Phone-Number-ID}}/messages
     * and attach a message object with type: "contact".
     * Then, add a contacts object.
     *
     * Documentation:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#contacts-object
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with "wamid".
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp Business.
     * @param string $recipientPhoneNumber Número del destinatario del mensaje.
     * @param array $contacts Lista de contactos que se enviarán.
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendContactMessage(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        array $contacts
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $recipientPhoneNumber,
            'type' => 'contacts',
            'contacts' => $contacts,
        ];

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post(
                "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages",
                $payload
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible enviar el contacto por WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Send Reply to Contact Message
     *
     * To send contact messages, make a POST call to /{{Phone-Number-ID}}/messages
     * and attach a message object with type: "contact".
     * Then, add a contacts object.
     *
     * Documentation:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#contacts-object
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with "wamid".
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp Business.
     * @param string $recipientPhoneNumber Número del destinatario del mensaje.
     * @param string $messageId ID del mensaje al que se responderá.
     * @param array $contacts Lista de contactos que se enviarán.
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendReplyToContactMessage(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $messageId,
        array $contacts
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $recipientPhoneNumber,
            'context' => [
                'message_id' => $messageId,
            ],
            'type' => 'contacts',
            'contacts' => $contacts,
        ];

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post(
                "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages",
                $payload
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible enviar los contactos como respuesta al mensaje.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Send Location Message
     *
     * To send location messages, make a POST call to /{{Phone-Number-ID}}/messages
     * and attach a message object with type = "location". Then, add a location object.
     *
     * For more information on the structure of Location Object, see:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#location-object
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with "wamid".
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp Business.
     * @param string $recipientPhoneNumber Número del destinatario.
     * @param float $latitude Latitud de la ubicación.
     * @param float $longitude Longitud de la ubicación.
     * @param string|null $locationName Nombre del lugar.
     * @param string|null $address Dirección de la ubicación.
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendLocationMessage(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        float $latitude,
        float $longitude,
        ?string $locationName = null,
        ?string $address = null
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $location = [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];

        if ($locationName !== null && trim($locationName) !== '') {
            $location['name'] = $locationName;
        }

        if ($address !== null && trim($address) !== '') {
            $location['address'] = $address;
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipientPhoneNumber,
            'type' => 'location',
            'location' => $location,
        ];

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post(
                "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages",
                $payload
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible enviar la ubicación por WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Send Reply to Location Message
     *
     * To send location messages, make a POST call to /{{Phone-Number-ID}}/messages
     * and attach a message object with type = "location". Then, add a location object.
     *
     * For more information on the structure of Location Object, see:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#location-object
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with "wamid".
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp Business.
     * @param string $recipientPhoneNumber Número del destinatario.
     * @param string $messageId ID del mensaje al que se responderá.
     * @param float $latitude Latitud de la ubicación.
     * @param float $longitude Longitud de la ubicación.
     * @param string|null $locationName Nombre del lugar.
     * @param string|null $address Dirección de la ubicación.
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendReplyToLocationMessage(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $messageId,
        float $latitude,
        float $longitude,
        ?string $locationName = null,
        ?string $address = null
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $location = [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];

        if ($locationName !== null && trim($locationName) !== '') {
            $location['name'] = $locationName;
        }

        if ($address !== null && trim($address) !== '') {
            $location['address'] = $address;
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipientPhoneNumber,
            'context' => [
                'message_id' => $messageId,
            ],
            'type' => 'location',
            'location' => $location,
        ];

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post(
                "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages",
                $payload
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible enviar la ubicación como respuesta al mensaje.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Send Message Template Text
     *
     * You need to create a message template before you can send one.
     * See Create Template Using the WhatsApp Manager for more information.
     * If your account is not verified yet, you can use one of the pre-approved templates.
     *
     * To send a text-based message template, make a POST call to
     * /{{Phone-Number-ID}}/messages and attach a message object with type = "template".
     * Then, add a template object.
     *
     * Prerequisites:
     * - You need to create a message template through WhatsApp Manager or BM API.
     * - For an unverified account, pre-created message templates may be available.
     *
     * Template creation using WhatsApp Manager:
     * https://developers.facebook.com/docs/whatsapp/message-templates/creation#step-1--create-template-using-the-whatsapp-manager
     *
     * WhatsApp Manager:
     * https://developers.facebook.com/docs/whatsapp/message-templates/creation
     *
     * Creating message templates using BM API:
     * https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates#creating-message-templates
     *
     * Pre-created message templates:
     * https://www.facebook.com/business/help/722393685250070
     *
     * Message Objects:
     * - Template Object:
     *   https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#template-object
     * - Component Object:
     *   https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#components-object
     * - Parameter Object:
     *   https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#parameter-object
     * - Currency Object:
     *   https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#currency-object
     * - Date_Time Object:
     *   https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#date-time-object
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with "wamid".
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp Business.
     * @param string $recipientPhoneNumber Número del destinatario.
     * @param string $templateName Nombre de la plantilla aprobada.
     * @param string $languageCode Código de idioma y región de la plantilla, por ejemplo es o es_CO.
     * @param array $components Componentes y parámetros dinámicos de la plantilla.
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessageTemplateText(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $templateName,
        string $languageCode,
        array $components = []
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipientPhoneNumber,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => [
                    'code' => $languageCode,
                ],
            ],
        ];

        if (!empty($components)) {
            $payload['template']['components'] = $components;
        }

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post(
                "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages",
                $payload
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible enviar la plantilla de WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Send Message Template Media
     *
     * Media message templates allow you to send media content as a header component
     * beyond text template with parameters in the body component.
     *
     * Before sending a message template, you need to create one.
     * See Create Template Using the WhatsApp Manager for more information.
     * If your account is not verified yet, you can use one of our pre-approved templates.
     *
     * To send a media-based message template, make a POST call to
     * /{{Phone-Number-ID}}/messages and attach a message object with type = template.
     * Then, add a template object.
     *
     * Prerequisites:
     * - You need to create a message template through WhatsApp Manager or BM API.
     * - For an unverified account, Meta provides pre-created message templates.
     *
     * Message Objects:
     * - Template Object
     * - Component Object
     * - Parameter Object
     * - Currency Object
     * - Date_Time Object
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with wamid.
     *
     * Documentation:
     * https://developers.facebook.com/docs/whatsapp/message-templates/creation
     * https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates#creating-message-templates
     * https://www.facebook.com/business/help/722393685250070
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#template-object
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#components-object
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#parameter-object
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#currency-object
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#date-time-object
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp Business.
     * @param string $recipientPhoneNumber Número del destinatario en formato internacional.
     * @param string $templateName Nombre exacto de la plantilla aprobada.
     * @param string $languageCode Código de idioma de la plantilla, por ejemplo es_CO o en_US.
     * @param string $mediaType Tipo de multimedia del encabezado: image, video o document.
     * @param string $mediaUrl URL pública del archivo multimedia.
     * @param array $bodyParameters Parámetros dinámicos del cuerpo de la plantilla.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessageTemplateMedia(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $templateName,
        string $languageCode,
        string $mediaType,
        string $mediaUrl,
        array $bodyParameters = []
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $components = [
            [
                'type' => 'header',
                'parameters' => [
                    [
                        'type' => $mediaType,
                        $mediaType => [
                            'link' => $mediaUrl,
                        ],
                    ],
                ],
            ],
        ];

        // Agregar parámetros dinámicos al cuerpo, si existen.
        if (!empty($bodyParameters)) {
            $components[] = [
                'type' => 'body',
                'parameters' => $bodyParameters,
            ];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipientPhoneNumber,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => [
                    'code' => $languageCode,
                ],
                'components' => $components,
            ],
        ];

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/messages", $payload);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo enviar la plantilla multimedia de WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Send Message Template Interactive
     *
     * Before sending a message template, you need to create one.
     * See Create Template Using the WhatsApp Manager for more information.
     * If your account is not verified yet, you can use one of our pre-approved templates.
     *
     * To send an interactive message template, make a POST call to
     * /{{Phone-Number-ID}}/messages and attach a message object with type=template.
     * Then, add a template object with your chosen button.
     *
     * Interactive message templates expand the content you can send recipients
     * beyond the standard message template and media messages template types to
     * include interactive buttons using the components object.
     *
     * There are two types of predefined buttons offered:
     * - Call-to-Action: Allows your customer to call a phone number and visit a website.
     * - Quick Reply: Allows your customer to return a simple text message.
     *
     * These buttons can be attached to text messages or media messages.
     * Once your interactive message templates have been created and approved,
     * you can use them in notification messages as well as customer service/care messages.
     *
     * Message Objects:
     * - Template Object
     * - Component Object
     * - Parameter Object
     * - Currency Object
     * - Date_Time Object
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with wamid.
     *
     * Documentation:
     * https://developers.facebook.com/docs/whatsapp/message-templates/creation
     * https://www.facebook.com/business/help/722393685250070
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#template-object
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#components-object
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#parameter-object
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#currency-object
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#date-time-object
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp Business.
     * @param string $recipientPhoneNumber Número del destinatario en formato internacional.
     * @param string $templateName Nombre exacto de la plantilla aprobada.
     * @param string $languageCode Código de idioma de la plantilla, por ejemplo es_CO o en_US.
     * @param array $components Componentes de la plantilla: header, body y button.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessageTemplateInteractive(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $templateName,
        string $languageCode,
        array $components = []
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipientPhoneNumber,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => [
                    'code' => $languageCode,
                ],
            ],
        ];

        if (!empty($components)) {
            $payload['template']['components'] = $components;
        }

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post(
                "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages",
                $payload
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo enviar la plantilla interactiva de WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Send List Message
     *
     * To send a list message, make a POST call to /messages and attach a
     * message object with type = interactive. Then, set the type of the
     * interactive object = list. Finally, include the corresponding interactive object.
     *
     * For more reference information about interactive objects, see Interactive Object.
     *
     * Documentation:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#interactive-object
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp Business.
     * @param string $recipientPhoneNumber Número del destinatario en formato internacional.
     * @param string $headerText Texto del encabezado.
     * @param string $bodyText Texto principal del mensaje.
     * @param string $buttonText Texto del botón que abre la lista.
     * @param array $sections Secciones y opciones que conforman la lista.
     * @param string|null $footerText Texto opcional del pie de página.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendListMessage(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $headerText,
        string $bodyText,
        string $buttonText,
        array $sections,
        ?string $footerText = null
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $interactive = [
            'type' => 'list',
            'header' => [
                'type' => 'text',
                'text' => $headerText,
            ],
            'body' => [
                'text' => $bodyText,
            ],
            'action' => [
                'button' => $buttonText,
                'sections' => $sections,
            ],
        ];

        if ($footerText !== null && $footerText !== '') {
            $interactive['footer'] = [
                'text' => $footerText,
            ];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipientPhoneNumber,
            'type' => 'interactive',
            'interactive' => $interactive,
        ];

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post(
                "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages",
                $payload
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo enviar el mensaje interactivo de lista por WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Send Reply to List Message
     *
     * To send a list message, make a POST call to /messages and attach a
     * message object with type = interactive. Then, set the type of the
     * interactive object = list. Finally, include the corresponding interactive object.
     *
     * This method replies to a previous message by including context.message_id
     * in the request payload.
     *
     * For more reference information about interactive objects, see Interactive Object.
     *
     * Documentation:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#interactive-object
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp Business.
     * @param string $recipientPhoneNumber Número del destinatario en formato internacional.
     * @param string $messageId ID del mensaje anterior al que se responderá.
     * @param string $headerText Texto del encabezado de la lista.
     * @param string $bodyText Texto principal del mensaje.
     * @param string $buttonText Texto del botón que abre la lista.
     * @param array $sections Secciones y opciones de la lista.
     * @param string|null $footerText Texto opcional del pie de página.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendReplyToListMessage(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $messageId,
        string $headerText,
        string $bodyText,
        string $buttonText,
        array $sections,
        ?string $footerText = null
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $interactive = [
            'type' => 'list',
            'header' => [
                'type' => 'text',
                'text' => $headerText,
            ],
            'body' => [
                'text' => $bodyText,
            ],
            'action' => [
                'button' => $buttonText,
                'sections' => $sections,
            ],
        ];

        if ($footerText !== null && $footerText !== '') {
            $interactive['footer'] = [
                'text' => $footerText,
            ];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipientPhoneNumber,
            'context' => [
                'message_id' => $messageId,
            ],
            'type' => 'interactive',
            'interactive' => $interactive,
        ];

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post(
                "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages",
                $payload
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo enviar la respuesta interactiva de lista por WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Send Reply Button
     *
     * To send interactive messages, make a POST call to /PHONE_NUMBER_ID/messages
     * (see Get Phone Number) and attach a message object with type=interactive.
     * Then, add an interactive object.
     *
     * For more reference information about interactive objects, see Interactive Object.
     *
     * Response:
     * A successful response includes an object with an identifier prefixed with wamid.
     * Use the ID listed after wamid to track your message status.
     *
     * Documentation:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/phone-numbers
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#interactive-object
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp Business.
     * @param string $recipientPhoneNumber Número del destinatario en formato internacional.
     * @param string $bodyText Texto principal del mensaje.
     * @param array $buttons Botones de respuesta, cada uno con id y title.
     * @param string|null $headerText Texto opcional del encabezado.
     * @param string|null $footerText Texto opcional del pie de página.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendReplyButton(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $bodyText,
        array $buttons,
        ?string $headerText = null,
        ?string $footerText = null
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        // WhatsApp permite entre 1 y 3 botones de respuesta rápida.
        if (count($buttons) < 1 || count($buttons) > 3) {
            return response()->json([
                'success' => false,
                'message' => 'Debes proporcionar entre 1 y 3 botones de respuesta.',
            ], 422);
        }

        $formattedButtons = [];

        foreach ($buttons as $button) {
            if (
                !isset($button['id'], $button['title']) ||
                !is_string($button['id']) ||
                !is_string($button['title']) ||
                trim($button['id']) === '' ||
                trim($button['title']) === ''
            ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cada botón debe contener un id y un title válidos.',
                ], 422);
            }

            $formattedButtons[] = [
                'type' => 'reply',
                'reply' => [
                    'id' => $button['id'],
                    'title' => $button['title'],
                ],
            ];
        }

        $interactive = [
            'type' => 'button',
            'body' => [
                'text' => $bodyText,
            ],
            'action' => [
                'buttons' => $formattedButtons,
            ],
        ];

        if ($headerText !== null && $headerText !== '') {
            $interactive['header'] = [
                'type' => 'text',
                'text' => $headerText,
            ];
        }

        if ($footerText !== null && $footerText !== '') {
            $interactive['footer'] = [
                'text' => $footerText,
            ];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipientPhoneNumber,
            'type' => 'interactive',
            'interactive' => $interactive,
        ];

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post(
                "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages",
                $payload
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo enviar el mensaje con botones de respuesta.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Mark Message As Read
     *
     * When you receive an incoming message from Webhooks, you could use
     * messages endpoint to change the status of it to read.
     *
     * We recommend marking incoming messages as read within 30 days of receipt.
     *
     * Note: you cannot mark outgoing messages you sent as read.
     *
     * You need to obtain the message_id of the incoming message from Webhooks.
     *
     * For a more in depth guide for marking messages as read, see
     * Guide: Mark Messages as Read.
     *
     * Documentation:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/guides/mark-message-as-read
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp Business.
     * @param string $messageId ID del mensaje entrante recibido mediante el webhook.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markMessageAsRead(
        string $accessToken,
        string $phoneNumberId,
        string $messageId
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $payload = [
            'messaging_product' => 'whatsapp',
            'status' => 'read',
            'message_id' => $messageId,
        ];

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->put(
                "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages",
                $payload
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo marcar el mensaje de WhatsApp como leído.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json($response->json());
    }

    /**
     * Send Single Product Message
     *
     * To send a single product message, make a POST call to the
     * /{{Version}}/{{Phone-Number-ID}}/messages endpoint.
     *
     * This request uses an interactive object and parameter type should be
     * set to "interactive". The interactive parameter must also be set to
     * the interactive object associated with the single product message.
     *
     * Parameters:
     * - messaging_product: Required. Always set this value to "whatsapp".
     * - recipient_type: Optional. Currently, messages can only be sent to
     *   individuals. Set this value to "individual". Default: individual.
     * - to: Required. WhatsApp ID or phone number of the recipient.
     * - type: Required. Set this value to "interactive".
     * - interactive: Required. Interactive object associated with the
     *   single product message.
     *
     * Interactive Object Parameters specific to Single Product Messages:
     * - type: Required. Set this value to "product".
     * - body: Optional. Body text displayed with the product.
     * - footer: Optional. Footer text displayed with the product.
     * - action: Required. Contains the catalog ID and product retailer ID.
     *   - catalog_id: ID of the WhatsApp product catalog.
     *   - product_retailer_id: Retailer ID of the product in the catalog.
     *
     * Documentation:
     * - Phone Numbers, Formatting:
     *   https://developers.facebook.com/docs/whatsapp/cloud-api/reference/phone-numbers#formatting
     * - Interactive Object:
     *   https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#interactive-object
     *
     * Endpoint:
     * POST /{Phone-Number-ID}/messages
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número o WhatsApp ID del destinatario.
     * @param string $catalogId ID del catálogo de productos de Meta.
     * @param string $productRetailerId ID del producto dentro del catálogo.
     * @param string|null $bodyText Texto opcional que acompaña al producto.
     * @param string|null $footerText Texto opcional del pie del mensaje.
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendSingleProductMessage(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $catalogId,
        string $productRetailerId,
        ?string $bodyText = null,
        ?string $footerText = null
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        $interactive = [
            'type' => 'product',
            'action' => [
                'catalog_id' => $catalogId,
                'product_retailer_id' => $productRetailerId,
            ],
        ];

        if ($bodyText !== null && trim($bodyText) !== '') {
            $interactive['body'] = [
                'text' => $bodyText,
            ];
        }

        if ($footerText !== null && trim($footerText) !== '') {
            $interactive['footer'] = [
                'text' => $footerText,
            ];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipientPhoneNumber,
            'type' => 'interactive',
            'interactive' => $interactive,
        ];

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, $payload);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible enviar el mensaje de producto individual.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json([
            'success' => true,
            'message' => 'Mensaje de producto individual enviado correctamente.',
            'data' => $response->json(),
        ]);
    }

    /**
     * Send Multi-Product Message
     *
     * To send a multi-product message, make a POST call to the
     * /{{Version}}/{{Phone-Number-ID}}/messages endpoint.
     *
     * This request uses an interactive object and parameter type should be
     * set to "interactive". The interactive parameter must also be set to
     * the interactive object associated with the multi-product message.
     *
     * Parameters:
     * - messaging_product: Required. Always set this value to "whatsapp".
     * - recipient_type: Optional. Currently, messages can only be sent to
     *   individuals. Set this value to "individual". Default: individual.
     * - to: Required. WhatsApp ID or phone number of the recipient.
     * - type: Required. Set this value to "interactive".
     * - interactive: Required. Interactive object associated with the
     *   multi-product message.
     *
     * Interactive Object Parameters specific to Multi-Product Messages:
     * - type: Required. Set this value to "product_list".
     * - header: Optional header object. For a text header, use type "text"
     *   and provide the text property.
     * - body: Required body object containing the message text.
     * - footer: Optional footer object containing the footer text.
     * - action: Required action object containing:
     *   - catalog_id: ID of the WhatsApp product catalog.
     *   - sections: Array of sections to display.
     *     - title: Section title.
     *     - product_items: Array of products belonging to the section.
     *       - product_retailer_id: Product SKU/retailer ID in the catalog.
     *
     * Documentation:
     * - Phone Numbers, Formatting:
     *   https://developers.facebook.com/docs/whatsapp/cloud-api/reference/phone-numbers#formatting
     * - Interactive Object:
     *   https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#interactive-object
     *
     * Endpoint:
     * POST /{Phone-Number-ID}/messages
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número o WhatsApp ID del destinatario.
     * @param string $catalogId ID del catálogo de productos de Meta.
     * @param array $sections Secciones y productos que se mostrarán.
     * @param string $bodyText Texto principal del mensaje.
     * @param string|null $headerText Texto opcional del encabezado.
     * @param string|null $footerText Texto opcional del pie del mensaje.
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMultiProductMessage(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $catalogId,
        array $sections,
        string $bodyText,
        ?string $headerText = null,
        ?string $footerText = null
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        $interactive = [
            'type' => 'product_list',
            'body' => [
                'text' => $bodyText,
            ],
            'action' => [
                'catalog_id' => $catalogId,
                'sections' => $sections,
            ],
        ];

        if ($headerText !== null && trim($headerText) !== '') {
            $interactive['header'] = [
                'type' => 'text',
                'text' => $headerText,
            ];
        }

        if ($footerText !== null && trim($footerText) !== '') {
            $interactive['footer'] = [
                'text' => $footerText,
            ];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipientPhoneNumber,
            'type' => 'interactive',
            'interactive' => $interactive,
        ];

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, $payload);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible enviar el mensaje de productos.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json([
            'success' => true,
            'message' => 'Mensaje de múltiples productos enviado correctamente.',
            'data' => $response->json(),
        ]);
    }

    /**
     * Send Catalog Message
     *
     * To send a catalog message, make a POST call to the
     * /{{Version}}/{{Phone-Number-ID}}/messages endpoint.
     *
     * This request uses an interactive object and parameter type should be
     * set to "interactive". The interactive parameter must also be set to
     * the interactive object associated with the catalog message.
     *
     * Original documentation describes this as a single product message,
     * but the provided cURL uses interactive.type = "catalog_message".
     *
     * Parameters:
     * - messaging_product: Required. Always set this value to "whatsapp".
     * - recipient_type: Optional. Currently, messages can only be sent to
     *   individuals. Set this value to "individual". Default: individual.
     * - to: Required. WhatsApp ID or phone number of the recipient.
     * - type: Required. Set this value to "interactive".
     * - interactive: Required. Interactive object associated with the
     *   catalog message.
     *
     * Interactive Object Parameters specific to Catalog Messages:
     * - type: Set this value to "catalog_message".
     * - body: Required message body containing the text.
     * - action: Required action object containing:
     *   - name: Set this value to "catalog_message".
     *   - parameters:
     *     - thumbnail_product_retailer_id: Optional product retailer ID
     *       used to display a product thumbnail in the catalog message.
     * - footer: Optional footer object containing the footer text.
     *
     * Documentation:
     * - Phone Numbers, Formatting:
     *   https://developers.facebook.com/docs/whatsapp/cloud-api/reference/phone-numbers#formatting
     * - Interactive Object:
     *   https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#interactive-object
     *
     * Endpoint:
     * POST /{Phone-Number-ID}/messages
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número o WhatsApp ID del destinatario.
     * @param string $bodyText Texto principal del mensaje del catálogo.
     * @param string|null $thumbnailProductRetailerId ID opcional del producto que aparecerá como miniatura.
     * @param string|null $footerText Texto opcional del pie del mensaje.
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendCatalogMessage(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $bodyText,
        ?string $thumbnailProductRetailerId = null,
        ?string $footerText = null
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        $action = [
            'name' => 'catalog_message',
        ];

        if (
            $thumbnailProductRetailerId !== null &&
            trim($thumbnailProductRetailerId) !== ''
        ) {
            $action['parameters'] = [
                'thumbnail_product_retailer_id' => $thumbnailProductRetailerId,
            ];
        }

        $interactive = [
            'type' => 'catalog_message',
            'body' => [
                'text' => $bodyText,
            ],
            'action' => $action,
        ];

        if ($footerText !== null && trim($footerText) !== '') {
            $interactive['footer'] = [
                'text' => $footerText,
            ];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipientPhoneNumber,
            'type' => 'interactive',
            'interactive' => $interactive,
        ];

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, $payload);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible enviar el mensaje del catálogo.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json([
            'success' => true,
            'message' => 'Mensaje del catálogo enviado correctamente.',
            'data' => $response->json(),
        ]);
    }

    /**
     * Send Catalog Template Message
     *
     * To send a catalog template message, make a POST call to the
     * /{{Version}}/{{Phone-Number-ID}}/messages endpoint.
     *
     * The provided cURL uses type = "template", not "interactive".
     * It sends a template with a body component and a CATALOG button.
     *
     * Parameters:
     * - messaging_product: Required. Always set this value to "whatsapp".
     * - recipient_type: Optional. Currently, messages can only be sent to
     *   individuals. Set this value to "individual". Default: individual.
     * - to: Required. WhatsApp ID or phone number of the recipient.
     * - type: Required. Set this value to "template".
     * - template: Required. Template object containing:
     *   - name: Name of the approved WhatsApp template.
     *   - language.code: Language code of the template.
     *   - components: Optional template components and parameters.
     *
     * Body Component:
     * - type: Set this value to "body".
     * - parameters: Array of text parameters, in the same order as the
     *   variables configured in the approved template body.
     *
     * Catalog Button Component:
     * - type: Set this value to "button".
     * - sub_type: Set this value to "CATALOG".
     * - index: Zero-based index of the button in the template.
     * - parameters: Array containing an action parameter:
     *   - type: Set this value to "action".
     *   - action.thumbnail_product_retailer_id: Product retailer ID used
     *     as the catalog button thumbnail.
     *
     * Original documentation comment:
     * "To send a single product message, make a POST call to the
     * /{{Version}}/phone_number/messages endpoint."
     *
     * Note: Although the original comment describes a single product
     * interactive message, the provided cURL sends a template message
     * with a CATALOG button. This method follows the provided cURL.
     *
     * Documentation:
     * - Phone Numbers, Formatting:
     *   https://developers.facebook.com/docs/whatsapp/cloud-api/reference/phone-numbers#formatting
     * - Interactive Object:
     *   https://developers.facebook.com/docs/whatsapp/cloud-api/reference/messages#interactive-object
     *
     * Endpoint:
     * POST /{Phone-Number-ID}/messages
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business emisor.
     * @param string $recipientPhoneNumber Número o WhatsApp ID del destinatario.
     * @param string $templateName Nombre de la plantilla aprobada.
     * @param string $languageCode Código de idioma de la plantilla, por ejemplo en_US.
     * @param array $bodyParameters Parámetros de texto del cuerpo, en el orden de las variables.
     * @param string|null $thumbnailProductRetailerId ID opcional del producto para la miniatura del botón de catálogo.
     * @param int $buttonIndex Índice del botón de catálogo, comenzando en cero.
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendCatalogTemplateMessage(
        string $accessToken,
        string $phoneNumberId,
        string $recipientPhoneNumber,
        string $templateName,
        string $languageCode,
        array $bodyParameters = [],
        ?string $thumbnailProductRetailerId = null,
        int $buttonIndex = 0
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/messages";

        $components = [];

        if (!empty($bodyParameters)) {
            $parameters = [];

            foreach ($bodyParameters as $value) {
                $parameters[] = [
                    'type' => 'text',
                    'text' => (string) $value,
                ];
            }

            $components[] = [
                'type' => 'body',
                'parameters' => $parameters,
            ];
        }

        if (
            $thumbnailProductRetailerId !== null &&
            trim($thumbnailProductRetailerId) !== ''
        ) {
            $components[] = [
                'type' => 'button',
                'sub_type' => 'CATALOG',
                'index' => $buttonIndex,
                'parameters' => [
                    [
                        'type' => 'action',
                        'action' => [
                            'thumbnail_product_retailer_id' => $thumbnailProductRetailerId,
                        ],
                    ],
                ],
            ];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $recipientPhoneNumber,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => [
                    'code' => $languageCode,
                ],
                'components' => $components,
            ],
        ];

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, $payload);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible enviar la plantilla de catálogo.',
                'error' => $response->json(),
            ], $response->status());
        }

        return response()->json([
            'success' => true,
            'message' => 'Plantilla de catálogo enviada correctamente.',
            'data' => $response->json(),
        ]);
    }
}
