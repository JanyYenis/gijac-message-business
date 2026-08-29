<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;

class PhoneNumbersService
{
    /**
     * Obtiene todos los números de teléfono asociados a una cuenta
     * de WhatsApp Business (WABA) especificada mediante el WABA-ID.
     *
     * Permite obtener el ID (id) del número de teléfono que se desea
     * utilizar para enviar mensajes mediante WhatsApp Business Cloud API.
     *
     * Parámetros de respuesta:
     *
     * - verified_name: Nombre verificado asociado al número de teléfono.
     *
     * - display_phone_number: Representación del número de teléfono
     *   que se muestra al usuario.
     *
     * - id: ID asociado al número de teléfono.
     *
     * - quality_rating: Calificación de calidad del número de teléfono,
     *   basada en cómo han sido recibidos los mensajes por los destinatarios
     *   durante los últimos días.
     *
     * Valores posibles de quality_rating:
     *
     * - Green: Calidad alta.
     * - Yellow: Calidad media.
     * - Red: Calidad baja.
     * - NA: La calidad aún no ha sido determinada.
     *
     * Para obtener más información sobre la calificación de calidad:
     * https://www.facebook.com/business/help/896873687365001
     */
    public function getPhoneNumbers(string $version, string $accessToken, string $wabaId)
    {
        $response = Http::withToken($accessToken)
            ->get("https://graph.facebook.com/{$version}/{$wabaId}/phone_numbers");

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al obtener los números de teléfono de la cuenta de WhatsApp Business.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Cuando se consultan todos los números de teléfono asociados
     * a una cuenta de WhatsApp Business (WABA), cada número tiene
     * un ID único.
     *
     * Este método permite consultar directamente la información
     * de un número de teléfono utilizando su Phone Number ID.
     *
     * Parámetros de respuesta:
     *
     * - verified_name: Nombre verificado asociado al número de teléfono.
     *
     * - display_phone_number: Representación del número de teléfono
     *   que se muestra al usuario.
     *
     * - id: ID asociado al número de teléfono.
     *
     * - quality_rating: Calificación de calidad del número de teléfono,
     *   basada en cómo han sido recibidos los mensajes por los destinatarios
     *   durante los últimos días.
     *
     * Valores posibles de quality_rating:
     *
     * - Green: Calidad alta.
     * - Yellow: Calidad media.
     * - Red: Calidad baja.
     * - NA: La calidad aún no ha sido determinada.
     *
     * Para obtener más información sobre la calificación de calidad:
     * https://www.facebook.com/business/help/896873687365001
     */
    public function getPhoneNumber(string $version, string $accessToken, string $phoneNumberId)
    {
        $response = Http::withToken($accessToken)
            ->get("https://graph.facebook.com/{$version}/{$phoneNumberId}");

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al obtener la información del número de teléfono.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Incluye el parámetro de consulta fields=name_status para obtener
     * el estado del nombre para mostrar asociado a un número de teléfono
     * específico.
     *
     * Este campo se encuentra actualmente en fase beta y no está disponible
     * para todos los desarrolladores.
     *
     * Parámetros:
     *
     * - name_status: Estado del nombre para mostrar asociado al número
     *   de teléfono.
     */
    public function getPhoneNameStatus(string $version, string $accessToken, string $phoneNumberId)
    {
        $response = Http::withToken($accessToken)
            ->get("https://graph.facebook.com/{$version}/{$phoneNumberId}", [
                'fields' => 'name_status',
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al obtener el estado del nombre para mostrar del número de teléfono.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Obtiene los números de teléfono asociados a una cuenta de WhatsApp
     * Business (WABA), permitiendo seleccionar los campos específicos
     * que se desean consultar y aplicar filtros sobre los resultados.
     *
     * Campos solicitados:
     *
     * - id: ID asociado al número de teléfono.
     *
     * - is_official_business_account: Indica si el número pertenece
     *   a una cuenta comercial oficial.
     *
     * - display_phone_number: Representación del número de teléfono
     *   que se muestra al usuario.
     *
     * - verified_name: Nombre verificado asociado al número de teléfono.
     *
     * En este caso se aplica un filtro para obtener únicamente los números
     * cuyo account_mode sea SANDBOX.
     *
     * Guía oficial:
     * https://developers.facebook.com/docs/whatsapp/business-management-api/manage-phone-numbers#filter-phone-numbers
     *
     * Referencia del endpoint:
     * https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account/phone_numbers/
     */
    public function getSandboxPhoneNumbers(
        string $version,
        string $accessToken,
        string $wabaId
    ) {
        $response = Http::withToken($accessToken)
            ->get("https://graph.facebook.com/{$version}/{$wabaId}/phone_numbers", [
                'fields' => 'id,is_official_business_account,display_phone_number,verified_name',
                'filtering' => [
                    [
                        'field' => 'account_mode',
                        'operator' => 'EQUAL',
                        'value' => 'SANDBOX',
                    ],
                ],
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al obtener los números de teléfono de la cuenta de WhatsApp Business.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Solicita un código de verificación para el número de teléfono que
     * se desea utilizar para enviar mensajes a los clientes.
     *
     * El número de teléfono debe verificarse mediante SMS o llamada de voz.
     *
     * Para solicitar el código de verificación mediante Graph API se realiza
     * una petición POST a {{PHONE_NUMBER_ID}}/request_code.
     *
     * Parámetros:
     *
     * - code_method: Método utilizado para enviar el código de verificación.
     *   Valores permitidos:
     *   - SMS: Envía el código mediante mensaje de texto.
     *   - VOICE: Envía el código mediante una llamada de voz.
     *
     * - locale: Configuración regional utilizada durante el proceso
     *   de verificación. Por ejemplo: es_ES.
     *
     * - language: Idioma utilizado para la comunicación del proceso
     *   de verificación. Por ejemplo: es_ES.
     *
     * Después de realizar correctamente la solicitud, Meta enviará el código
     * de verificación mediante el método seleccionado en code_method.
     *
     * Para finalizar la verificación del número, se debe utilizar posteriormente
     * el endpoint Verify Code.
     *
     * Referencia:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/phone-numbers#verify
     */
    public function requestPhoneVerificationCode(
        string $version,
        string $accessToken,
        string $phoneNumberId,
        string $codeMethod = 'SMS',
        string $locale = 'es_ES',
        string $language = 'es_ES'
    ) {
        $response = Http::withToken($accessToken)
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/request_code", [
                'code_method' => $codeMethod,
                'locale' => $locale,
                'language' => $language,
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al solicitar el código de verificación del número de teléfono.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Después de recibir el código de verificación mediante SMS o llamada
     * de voz a través de Request Verification Code, este método permite
     * verificar el código recibido.
     *
     * Para verificar el código se realiza una petición POST a:
     *
     * {{PHONE_NUMBER_ID}}/verify_code
     *
     * Parámetros:
     *
     * - code: Código numérico de verificación recibido después de solicitar
     *   el código mediante el endpoint {{PHONE_NUMBER_ID}}/request_code.
     *   Este parámetro es obligatorio y debe ser una cadena numérica.
     *
     * Una vez verificado correctamente el código, el número de teléfono
     * quedará verificado para su utilización con WhatsApp Business Cloud API.
     *
     * Referencia:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/phone-numbers#verify
     */
    public function verifyPhoneCode(
        string $version,
        string $accessToken,
        string $phoneNumberId,
        string $code
    ) {
        $response = Http::withToken($accessToken)
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/verify_code", [
                'code' => $code,
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al verificar el código del número de teléfono.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Permite establecer o cambiar el código de verificación en dos pasos
     * asociado al número de teléfono de WhatsApp Business.
     *
     * Después de cambiar el código, las futuras solicitudes que requieran
     * el PIN de verificación, como el cambio del nombre para mostrar,
     * deberán utilizar el nuevo código.
     *
     * Este endpoint permite configurar la verificación en dos pasos y
     * registrar un número de teléfono en la misma llamada a la API.
     *
     * Parámetros:
     *
     * - pin: PIN de 6 dígitos que se desea utilizar para la verificación
     *   en dos pasos. Este parámetro es obligatorio.
     *
     * El PIN debe ser un valor numérico de exactamente 6 dígitos.
     *
     * Referencia:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/registration#register-phone
     */
    public function changeTwoStepVerificationPin(
        string $version,
        string $accessToken,
        string $phoneNumberId,
        string $pin
    ) {
        $response = Http::withToken($accessToken)
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}", [
                'pin' => $pin,
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al cambiar el PIN de verificación en dos pasos.',
            'error' => $response->json(),
        ], $response->status());
    }
}
