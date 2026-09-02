<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class MediaService
{
    /**
     * Sube un archivo multimedia a WhatsApp Business.
     *
     * This request uploads a media file to WhatsApp. The parameters are specified
     * as form-data in the request body.
     *
     * Supported examples:
     * - Image: .jpeg / image/jpeg
     * - Audio: .ogg / audio/ogg
     * - Sticker: .webp / image/webp
     *
     * Endpoint:
     * POST /{PHONE_NUMBER_ID}/media
     *
     * El archivo se envía mediante multipart/form-data e incluye:
     * - messaging_product: whatsapp
     * - file: archivo multimedia
     *
     * @param string $version Versión de la API de Graph de Meta.
     * @param string $accessToken Token de acceso de WhatsApp Business.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp.
     * @param string $filePath Ruta local del archivo que se desea subir.
     * @param string $mimeType Tipo MIME del archivo.
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function uploadMedia(
        string $version,
        string $accessToken,
        string $phoneNumberId,
        string $filePath,
        string $mimeType
    ) {
        $response = Http::withToken($accessToken)
            ->attach(
                'file',
                file_get_contents($filePath),
                basename($filePath),
                [
                    'Content-Type' => $mimeType,
                ]
            )
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/media", [
                'messaging_product' => 'whatsapp',
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al subir el archivo multimedia a WhatsApp.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Recupera la URL temporal de un archivo multimedia de WhatsApp.
     *
     * To retrieve your media’s URL, make a GET call to /{{Media-ID}}.
     * Use the returned URL to download the media file. Note that clicking this
     * URL (i.e. performing a generic GET) will not return the media; you must
     * include an access token.
     *
     * También se puede utilizar el parámetro opcional `phone_number_id` para
     * comprobar que el archivo multimedia pertenece al número de teléfono
     * indicado antes de recuperar su URL.
     *
     * La URL devuelta por Meta es temporal y tiene una validez de 5 minutos.
     *
     * @param string $version Versión de la API de Graph de Meta.
     * @param string $accessToken Token de acceso de WhatsApp Business.
     * @param string $mediaId ID del archivo multimedia.
     * @param string|null $phoneNumberId ID del número de teléfono de WhatsApp.
     *
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getMediaUrl(
        string $version,
        string $accessToken,
        string $mediaId,
        ?string $phoneNumberId = null
    ) {
        $url = "https://graph.facebook.com/{$version}/{$mediaId}";

        $query = [];

        if ($phoneNumberId) {
            $query['phone_number_id'] = $phoneNumberId;
        }

        $response = Http::withToken($accessToken)
            ->get($url, $query);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al obtener la URL del archivo multimedia.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Sube un archivo (imagen, video o documento) usando la Resumable
     * Upload API de Meta y devuelve el "handle" que se usa como
     * header_handle al crear una plantilla con encabezado multimedia.
     *
     * OJO: esto NO es lo mismo que MediaService::uploadMedia(). Ese
     * método sube archivos al endpoint /{PHONE_NUMBER_ID}/media, usado
     * para enviar multimedia en mensajes. Para el encabezado de una
     * PLANTILLA, Meta exige este flujo distinto de 2 pasos:
     *
     * 1. Crear una sesión de carga:
     *    POST /{app-id}/uploads?file_length=...&file_type=...
     *
     * 2. Subir el archivo binario a esa sesión:
     *    POST /{upload_session_id}   (header "file_offset: 0")
     *    -> devuelve { "h": "<handle>" }
     *
     * Ese "h" es lo que va en:
     *   "example": { "header_handle": ["<handle>"] }
     * dentro del componente HEADER de la plantilla.
     *
     * Guías oficiales:
     * https://developers.facebook.com/docs/graph-api/guides/upload
     * https://developers.facebook.com/docs/whatsapp/business-management-api/message-templates/media-based-templates
     *
     * @param string $version    Versión de Graph API, ej. "v24.0".
     * @param string $accessToken Token de acceso (system user / app).
     * @param string $appId      ID de la app de Meta (App ID, no WABA ID).
     * @param string $filePath   Ruta local del archivo ya guardado en disco.
     * @param string $mimeType   Tipo MIME del archivo (ej. image/png).
     *
     * @return string El header_handle devuelto por Meta.
     *
     * @throws RuntimeException Si alguno de los dos pasos falla.
     */
    public function uploadHeaderMedia(
        string $version,
        string $accessToken,
        string $appId,
        string $filePath,
        string $mimeType
    ): string {
        if (! is_readable($filePath)) {
            throw new RuntimeException("No se pudo leer el archivo: {$filePath}");
        }

        $bytes = filesize($filePath);
        $fileName = basename($filePath);

        // 1. Crear la sesión de carga.
        $session = Http::withToken($accessToken)
            ->post("https://graph.facebook.com/{$version}/{$appId}/uploads", [
                'file_length' => $bytes,
                'file_type' => $mimeType,
                'file_name' => $fileName,
            ]);

        if (! $session->successful() || empty($session->json('id'))) {
            throw new RuntimeException(
                'No se pudo iniciar la sesión de carga en Meta: '.$session->body()
            );
        }

        $uploadSessionId = $session->json('id'); // formato: "upload:XYZ..."

        // 2. Subir el archivo binario a la sesión creada.
        $upload = Http::withHeaders([
                'Authorization' => "OAuth {$accessToken}",
                'file_offset' => '0',
            ])
            ->withBody(file_get_contents($filePath), $mimeType)
            ->post("https://graph.facebook.com/{$version}/{$uploadSessionId}");

        if (! $upload->successful() || empty($upload->json('h'))) {
            throw new RuntimeException(
                'No se pudo subir el archivo a Meta: '.$upload->body()
            );
        }

        return $upload->json('h');
    }
}
