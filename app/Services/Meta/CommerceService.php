<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;

class CommerceService
{
    /**
     * Consultar la configuración de comercio de un número de WhatsApp Business.
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de teléfono de WhatsApp Business.
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function getCommerceSettings(
        string $accessToken,
        string $phoneNumberId
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/whatsapp_commerce_settings";

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->get($url);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'No fue posible consultar la configuración de comercio de WhatsApp.',
            'error' => $response->json(),
        ], $response->status());
    }

    /**
     * Activar o actualizar la configuración de comercio de WhatsApp Business.
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $phoneNumberId ID del número de WhatsApp Business.
     * @param bool $isCartEnabled Indica si el carrito de compras está habilitado.
     * @param bool $isCatalogVisible Indica si el catálogo es visible.
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function setCommerceSettings(
        string $accessToken,
        string $phoneNumberId,
        bool $isCartEnabled = true,
        bool $isCatalogVisible = true
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $url = "https://graph.facebook.com/{$version}/{$phoneNumberId}/whatsapp_commerce_settings";

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($url, [
                'is_cart_enabled' => $isCartEnabled,
                'is_catalog_visible' => $isCatalogVisible,
            ]);

        if ($response->successful()) {
            return $response->json();
        }

        return response()->json([
            'success' => false,
            'message' => 'No fue posible actualizar la configuración de comercio de WhatsApp.',
            'error' => $response->json(),
        ], $response->status());
    }
}
