<?php

namespace App\Services\Meta\Flows;

use Illuminate\Support\Facades\Http;

class EncryptionService
{
    /**
     * Configura la clave pública de la empresa para WhatsApp Flows.
     *
     * Establece la clave pública que se utilizará para cifrar las solicitudes
     * del canal de datos (Data Channel) de WhatsApp Flows.
     *
     * La clave debe estar en formato PEM e incluir los encabezados
     * -----BEGIN PUBLIC KEY----- y -----END PUBLIC KEY-----.
     *
     * Endpoint:
     * POST /{PHONE-NUMBER-ID}/whatsapp_business_encryption
     *
     * Parámetros:
     * - accessToken: Token de acceso de Meta.
     * - phoneNumberId: ID del número de teléfono de WhatsApp Business.
     * - businessPublicKey: Clave pública de la empresa en formato PEM.
     *
     * Respuesta exitosa:
     * Devuelve la respuesta de Meta con el resultado de la operación.
     */
    public function setBusinessPublicKey(
        string $accessToken,
        string $phoneNumberId,
        string $businessPublicKey
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $response = Http::withToken($accessToken)
            ->asMultipart()
            ->post("https://graph.facebook.com/{$version}/{$phoneNumberId}/whatsapp_business_encryption", [
                [
                    'name' => 'business_public_key',
                    'contents' => $businessPublicKey,
                ],
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al configurar la clave pública para WhatsApp Flows.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Consulta la clave pública configurada para WhatsApp Business.
     *
     * Obtiene la información de la clave pública utilizada para cifrar
     * las solicitudes del canal de datos (Data Channel) de WhatsApp Flows.
     *
     * Endpoint:
     * GET /{PHONE-NUMBER-ID}/whatsapp_business_encryption
     *
     * Parámetros:
     * - accessToken: Token de acceso de Meta.
     * - phoneNumberId: ID del número de teléfono de WhatsApp Business.
     *
     * Respuesta exitosa:
     * Devuelve la información de la clave pública configurada.
     */
    public function getBusinessPublicKey(
        string $accessToken,
        string $phoneNumberId
    ) {
        $version = config('facebook.graph_version', env('FACEBOOK_GRAPH_VERSION', 'v26.0'));

        $response = Http::withToken($accessToken)
            ->get("https://graph.facebook.com/{$version}/{$phoneNumberId}/whatsapp_business_encryption");

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al consultar la clave pública de WhatsApp Business.',
            'error' => $response->json(),
        ], $response->status());
    }
}
