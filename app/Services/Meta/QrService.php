<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;

class QrService
{
    /**
     * Consultar la información de un código QR de WhatsApp Business.
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business.
     * @param string $qrCodeId ID del código QR que se desea consultar.
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getQrCode(
        string $accessToken,
        string $phoneNumberId,
        string $qrCodeId
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/message_qrdls/"
            . rawurlencode($qrCodeId);

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->get($url);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'No fue posible consultar la información del código QR de WhatsApp Business.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Consultar todos los códigos QR de un número de WhatsApp Business.
     *
     * Utiliza los campos predeterminados devueltos por Meta.
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business.
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getAllQrCodes(
        string $accessToken,
        string $phoneNumberId
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/message_qrdls";

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->get($url);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'No fue posible consultar los códigos QR de WhatsApp Business.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Consultar los códigos QR de WhatsApp Business con campos específicos.
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business.
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getAllQrCodesSpecificFields(
        string $accessToken,
        string $phoneNumberId
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/message_qrdls";

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->get($url, [
                'fields' => 'code,prefilled_message,qr_image_url.format(SVG)',
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'No fue posible consultar los códigos QR con los campos solicitados.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Obtener la URL de la imagen SVG de un código QR de WhatsApp.
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp.
     * @param string $qrCodeId Código QR que se desea consultar.
     * @return mixed
     */
    public function getQrCodeSvgImageUrl(
        string $accessToken,
        string $phoneNumberId,
        string $qrCodeId
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->get(
                "https://graph.facebook.com/{$version}/{$phoneNumberId}/message_qrdls",
                [
                    'fields' => 'prefilled_message,deep_link_url,qr_image_url.format(SVG)',
                    'code' => $qrCodeId,
                ]
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible obtener la URL de la imagen SVG del código QR.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }

    /**
     * Obtener la URL de la imagen PNG de un código QR de WhatsApp.
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp.
     * @param string $qrCodeId Código QR que se desea consultar.
     * @return mixed
     */
    public function getQrCodePngImageUrl(
        string $accessToken,
        string $phoneNumberId,
        string $qrCodeId
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->get(
                "https://graph.facebook.com/{$version}/{$phoneNumberId}/message_qrdls",
                [
                    'fields' => 'prefilled_message,deep_link_url,qr_image_url.format(PNG)',
                    'code' => $qrCodeId,
                ]
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible obtener la URL de la imagen PNG del código QR.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }

    /**
     * Crear un código QR para WhatsApp Business.
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp.
     * @param string $prefilledMessage Mensaje precargado al escanear el QR.
     * @param bool $generateQrImage Indica si se debe generar la imagen del QR.
     * @return mixed
     */
    public function createQrCode(
        string $accessToken,
        string $phoneNumberId,
        string $prefilledMessage,
        bool $generateQrImage = true
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post(
                "https://graph.facebook.com/{$version}/{$phoneNumberId}/message_qrdls",
                [
                    'prefilled_message' => $prefilledMessage,
                    'generate_qr_image' => $generateQrImage,
                ]
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible crear el código QR de WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }

    /**
     * Actualizar el mensaje precargado de un código QR de WhatsApp Business.
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp.
     * @param string $qrCodeId Código identificador del QR que se actualizará.
     * @param string $prefilledMessage Nuevo mensaje precargado.
     * @return mixed
     */
    public function updateMessageQrCode(
        string $accessToken,
        string $phoneNumberId,
        string $qrCodeId,
        string $prefilledMessage
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post(
                "https://graph.facebook.com/{$version}/{$phoneNumberId}/message_qrdls",
                [
                    'prefilled_message' => $prefilledMessage,
                    'code' => $qrCodeId,
                ]
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible actualizar el código QR de WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }

    /**
     * Eliminar un código QR de WhatsApp Business.
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp.
     * @param string $qrCodeId Código identificador del QR que se eliminará.
     * @return mixed
     */
    public function deleteQrCode(
        string $accessToken,
        string $phoneNumberId,
        string $qrCodeId
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->delete(
                "https://graph.facebook.com/{$version}/{$phoneNumberId}/message_qrdls/{$qrCodeId}"
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible eliminar el código QR de WhatsApp.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }
}
