<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;

class BusinessPortfolioService
{
    /**
     * Obtener información específica de un portafolio empresarial de Meta.
     *
     * @param string $accessToken Token de acceso de Meta.
     * @param string $businessId Identificador del portafolio empresarial.
     * @return mixed
     */
    public function getBusinessPortfolio(
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
                "https://graph.facebook.com/{$version}/{$businessId}",
                [
                    'fields' => 'id,name,timezone_id',
                ]
            );

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'No fue posible obtener la información del portafolio empresarial.',
                'error' => $response->json(),
            ], $response->status());
        }

        return $response->json();
    }
}
