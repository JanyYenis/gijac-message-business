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

    'config_id'     => env('FACEBOOK_CONFIG_ID'),   // Configuration ID del Embedded Signup
    'graph_version' => env('FACEBOOK_GRAPH_VERSION', 'v26.0'),
];
