<?php

namespace App\Services\Meta;

use Illuminate\Support\Facades\Http;

class CatalogoService
{
    public function uploadProduct(string $version, string $catalogId, string $accessToken)
    {
        $catalogId = '671791141972412'; // Tu catálogo Servicios_Gijac_Web
        $accessToken = 'TU_TOKEN_DE_ACCESO';

        $response = Http::post("https://graph.facebook.com/{$version}/{$catalogId}/product_items", [
            'name' => 'GIJAC WEB',
            'description' => 'Descripción detallada',
            'retailer_id' => 'sku_unico_001',
            'availability' => 'in stock',
            'condition' => 'new',
            'price' => 1000000,
            'currency' => 'COP',
            'link' => 'https://message-business.gijac.com/',
            'image_url' => 'https://message-business.gijac.com/img/logo_gmb.png',
            'brand' => 'Tu Marca',
            'access_token' => $accessToken
        ]);

        return $response->json();
    }

    public function getCatalogProducts(string $version, string $catalogId, string $accessToken)
    {
        $catalogId = '671791141972412';

        $response = Http::get("https://graph.facebook.com/v18.0/{$catalogId}/products", [
            'fields' => 'id,name,description,price,currency,availability,retailer_id,image_url',
            'access_token' => $accessToken,
            'limit' => 100 // Puedes ajustar el límite por página
        ]);

        if ($response->successful()) {
            return $response->json()['data'];
        }

        return $response->json();
    }
}
