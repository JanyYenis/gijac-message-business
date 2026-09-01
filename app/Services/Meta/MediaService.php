<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;

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
}
