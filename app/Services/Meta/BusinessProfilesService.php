<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;

class BusinessProfilesService
{
    /**
     * Crear una sesión de carga reanudable de archivos en Meta.
     *
     * Inicia una sesión de carga mediante el endpoint /app/uploads.
     * Meta devuelve un identificador de sesión que se utiliza posteriormente
     * para cargar los datos del archivo y consultar el estado de la carga.
     *
     * Documentación oficial:
     * https://developers.facebook.com/docs/graph-api/guides/upload#step-1--create-a-session
     *
     * Endpoint:
     * POST /{app-id}/uploads
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param int $fileLength Tamaño del archivo en bytes.
     * @param string $fileType Tipo MIME del archivo, por ejemplo image/jpeg.
     * @param string $fileName Nombre del archivo, por ejemplo myprofile.jpg.
     * @return \Illuminate\Http\JsonResponse|array
     */
    public function createResumableUploadSession(
        string $accessToken,
        int $fileLength,
        string $fileType = 'image/jpeg',
        string $fileName = 'myprofile.jpg'
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));
        $appId = config('facebook.client_id', env('FACEBOOK_CLIENT_ID'));

        if (empty($appId)) {
            return response()->json([
                'success' => false,
                'message' => 'No se ha configurado el ID de la aplicación de Meta.',
            ], 500);
        }

        if ($fileLength <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'El tamaño del archivo debe ser mayor que cero.',
            ], 422);
        }

        $response = Http::withToken($accessToken, 'OAuth')
            ->acceptJson()
            ->post("https://graph.facebook.com/{$version}/{$appId}/uploads", [
                'file_length' => $fileLength,
                'file_type' => $fileType,
                'file_name' => $fileName,
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al crear la sesión de carga en Meta.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Cargar los datos de un archivo mediante una sesión de carga reanudable de Meta.
     *
     * Envía el contenido binario del archivo a Meta utilizando el identificador
     * de sesión obtenido al crear la sesión de carga.
     *
     * El encabezado file_offset es obligatorio y debe indicar la posición
     * desde la cual se envían los datos. Para una carga inicial, normalmente es 0.
     *
     * Documentación oficial:
     * https://developers.facebook.com/docs/graph-api/guides/upload#step-2--initiate-upload
     *
     * Endpoint:
     * POST /{upload-id}
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $uploadId Identificador de la sesión de carga.
     * @param string $filePath Ruta local del archivo que se va a cargar.
     * @param int $fileOffset Posición inicial de los datos en bytes.
     * @param string|null $contentType Tipo MIME del archivo. Si es null, se detecta automáticamente.
     * @return \Illuminate\Http\JsonResponse|array
     */
    public function uploadResumableFileData(
        string $accessToken,
        string $uploadId,
        string $filePath,
        int $fileOffset = 0,
        ?string $contentType = null
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        if (!is_file($filePath) || !is_readable($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'El archivo no existe o no se puede leer.',
            ], 422);
        }

        if ($fileOffset < 0 || $fileOffset > filesize($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'El desplazamiento del archivo no es válido.',
            ], 422);
        }

        $contentType = $contentType
            ?: (mime_content_type($filePath) ?: 'application/octet-stream');

        $fileContents = file_get_contents($filePath);

        if ($fileContents === false) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible leer el contenido del archivo.',
            ], 500);
        }

        // El Upload-ID puede contener caracteres como :, /, ?, = y sig.
        // Se codifica para utilizarlo correctamente como segmento de la URL.
        $encodedUploadId = rawurlencode($uploadId);

        $response = Http::withHeaders([
            'Authorization' => 'OAuth ' . $accessToken,
            'file_offset' => (string) $fileOffset,
            'Content-Type' => $contentType,
        ])
            ->withBody($fileContents, $contentType)
            ->post("https://graph.facebook.com/{$version}/{$encodedUploadId}");

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al cargar los datos del archivo en Meta.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Consultar el estado de una sesión de carga reanudable de Meta.
     *
     * Obtiene la información de la sesión de carga, incluido el identificador
     * y el desplazamiento actual (file_offset) para reanudar la transferencia
     * si fue interrumpida.
     *
     * Documentación oficial:
     * https://developers.facebook.com/docs/graph-api/guides/upload#interruptions
     *
     * Endpoint:
     * GET /{upload-id}
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $uploadId Identificador completo de la sesión de carga.
     * @return \Illuminate\Http\JsonResponse|array
     */
    public function queryResumableUploadStatus(
        string $accessToken,
        string $uploadId
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        // Codificar el identificador completo para utilizarlo en la URL.
        $encodedUploadId = rawurlencode($uploadId);

        $response = Http::withHeaders([
            'Authorization' => 'OAuth ' . $accessToken,
            'Cache-Control' => 'no-cache',
        ])
            ->acceptJson()
            ->get("https://graph.facebook.com/{$version}/{$encodedUploadId}");

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al consultar el estado de la sesión de carga en Meta.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Obtener la información del perfil comercial de WhatsApp Business.
     *
     * Consulta los datos del perfil empresarial asociado al número de WhatsApp,
     * como dirección, descripción, categoría, correo electrónico, sitios web,
     * información "Acerca de" e imagen de perfil.
     *
     * Documentación oficial:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/business-profiles
     *
     * Endpoint:
     * GET /{phone-number-id}/whatsapp_business_profile
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp Business.
     * @param array $fields Campos que se desean consultar.
     * @return \Illuminate\Http\JsonResponse|array
     */
    public function getBusinessProfile(
        string $accessToken,
        string $phoneNumberId,
        array $fields = [
            'about',
            'address',
            'description',
            'email',
            'profile_picture_url',
            'websites',
            'vertical',
        ]
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->get("https://graph.facebook.com/{$version}/{$phoneNumberId}/whatsapp_business_profile", [
                'fields' => implode(',', $fields),
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al consultar el perfil comercial de WhatsApp Business.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Actualizar el perfil comercial de WhatsApp Business.
     *
     * Permite modificar los datos del perfil empresarial asociado a un número
     * de WhatsApp. Todos los campos son opcionales, excepto messaging_product,
     * que siempre debe ser "whatsapp".
     *
     * Para actualizar la imagen de perfil, utiliza profile_picture_handle
     * obtenido previamente mediante la API de carga reanudable de Meta.
     *
     * Documentación oficial:
     * https://developers.facebook.com/docs/whatsapp/cloud-api/reference/business-profiles
     *
     * Endpoint:
     * POST /{phone-number-id}/whatsapp_business_profile
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp Business.
     * @param array $data Datos del perfil que se desean actualizar.
     * @return \Illuminate\Http\JsonResponse|array
     */
    public function updateBusinessProfile(
        string $accessToken,
        string $phoneNumberId,
        array $data
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $allowedFields = [
            'address',
            'description',
            'vertical',
            'about',
            'email',
            'websites',
            'profile_picture_handle',
        ];

        // Conservar únicamente los campos admitidos.
        $profileData = array_intersect_key(
            $data,
            array_flip($allowedFields)
        );

        if (empty($profileData)) {
            return response()->json([
                'success' => false,
                'message' => 'Debes proporcionar al menos un campo del perfil para actualizar.',
            ], 422);
        }

        // Validar la cantidad máxima de sitios web.
        if (isset($profileData['websites'])) {
            if (!is_array($profileData['websites']) || count($profileData['websites']) > 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'El campo websites debe ser un arreglo con máximo dos sitios web.',
                ], 422);
            }

            foreach ($profileData['websites'] as $website) {
                if (
                    !is_string($website) ||
                    !filter_var($website, FILTER_VALIDATE_URL) ||
                    !in_array(strtolower(parse_url($website, PHP_URL_SCHEME) ?? ''), ['http', 'https'], true) ||
                    strlen($website) > 256
                ) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cada sitio web debe ser una URL HTTP o HTTPS válida de máximo 256 caracteres.',
                    ], 422);
                }
            }
        }

        // Validar longitudes de los campos de texto.
        $lengthLimits = [
            'address' => 256,
            'description' => 256,
            'about' => 139,
            'email' => 128,
        ];

        foreach ($lengthLimits as $field => $maxLength) {
            if (isset($profileData[$field])) {
                if (!is_string($profileData[$field]) || mb_strlen($profileData[$field]) > $maxLength) {
                    return response()->json([
                        'success' => false,
                        'message' => "El campo {$field} debe ser un texto de máximo {$maxLength} caracteres.",
                    ], 422);
                }
            }
        }

        if (isset($profileData['about']) && mb_strlen($profileData['about']) < 1) {
            return response()->json([
                'success' => false,
                'message' => 'El campo about debe contener al menos un carácter.',
            ], 422);
        }

        if (isset($profileData['email']) && !filter_var($profileData['email'], FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'success' => false,
                'message' => 'El correo electrónico proporcionado no es válido.',
            ], 422);
        }

        $allowedVerticals = [
            'UNDEFINED',
            'OTHER',
            'AUTO',
            'BEAUTY',
            'APPAREL',
            'EDU',
            'ENTERTAIN',
            'EVENT_PLAN',
            'FINANCE',
            'GROCERY',
            'GOVT',
            'HOTEL',
            'HEALTH',
            'NONPROFIT',
            'PROF_SERVICES',
            'RETAIL',
            'TRAVEL',
            'RESTAURANT',
            'NOT_A_BIZ',
        ];

        if (
            isset($profileData['vertical']) &&
            $profileData['vertical'] !== '' &&
            !in_array($profileData['vertical'], $allowedVerticals, true)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'La categoría comercial indicada no es válida.',
                'allowed_values' => $allowedVerticals,
            ], 422);
        }

        $payload = array_merge([
            'messaging_product' => 'whatsapp',
        ], $profileData);

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post(
                "https://graph.facebook.com/{$version}/{$phoneNumberId}/whatsapp_business_profile",
                $payload
            );

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar el perfil comercial de WhatsApp Business.',
            'error' => $response->json(),
        ], $response->status());
    }
}
