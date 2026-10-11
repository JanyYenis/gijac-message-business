<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;

class BillingService
{
    /**
     * Obtener las líneas de crédito asociadas a un portafolio empresarial de Meta.
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $businessId ID del portafolio empresarial.
     * @return mixed
     */
    public function getCreditLines(
        string $accessToken,
        string $businessId
    ) {
        $version = config(
            'facebook.graph_version',
            env('FACEBOOK_GRAPH_VERSION', 'v26.0')
        );

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->get(
                "https://graph.facebook.com/{$version}/{$businessId}/extendedcredits"
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible obtener las líneas de crédito del portafolio empresarial.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }
}
