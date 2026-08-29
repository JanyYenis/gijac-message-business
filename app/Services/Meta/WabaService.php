<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;

class WabaService
{
    public function getWaba(string $version, string $accessToken, string $wabaId): array
    {
        $response = Http::withToken($accessToken)
            ->get("https://graph.facebook.com/{$version}/{$wabaId}");

        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'data' => $response->json(),
        ];
    }

    public function getOwnedWaba(string $version, string $accessToken, string $businessId): array
    {
        $response = Http::withToken($accessToken)
            ->get("https://graph.facebook.com/{$version}/{$businessId}/owned_whatsapp_business_accounts");

        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'data' => $response->json(),
        ];
    }

    public function getSharedWaba(string $version, string $accessToken, string $businessId): array
    {
        $response = Http::withToken($accessToken)
            ->get(
                "https://graph.facebook.com/{$version}/{$businessId}/client_whatsapp_business_accounts"
            );

        if ($response->failed()) {
            return [
                'success' => false,
                'status' => $response->status(),
                'error' => $response->json(),
            ];
        }

        return [
            'success' => true,
            'data' => $response->json('data', []),
            'paging' => $response->json('paging'),
        ];
    }
}
