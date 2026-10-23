<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Facebook App ID
    |--------------------------------------------------------------------------
    |
    | This value is the Facebook App ID used for authentication and API requests.
    | You can find this value in your Facebook Developer account.
    |
    */

    'client_id' => env('FACEBOOK_CLIENT_ID', 'your-facebook-app-id'),

    /*
    |--------------------------------------------------------------------------
    | Facebook App Secret
    |--------------------------------------------------------------------------
    |
    | This value is the Facebook App Secret used for authentication and API requests.
    | You can find this value in your Facebook Developer account.
    |
    */

    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),

    // Token que se escribe en el panel de Meta al configurar el webhook (el nombre de la variable conserva el typo original)
    'webhook_token' => env('FACEBOOK_WEBOOK_TOKEN'),

    'config_id'     => env('FACEBOOK_CONFIG_ID'),   // Configuration ID del Embedded Signup
    'graph_version' => env('FACEBOOK_GRAPH_VERSION', 'v26.0'),
    'envio_por_segundo' => env('WHATSAPP_ENVIO_POR_SEGUNDO', 10),
    'mm_api' => env('WHATSAPP_MM_API', true),
    'mm_policy' => env('WHATSAPP_MM_POLICY', 'STRICT'),
    'default_pin' => env('WHATSAPP_DEFAULT_PIN', 762190),
];
