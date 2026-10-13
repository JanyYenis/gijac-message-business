<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_OAUTH_ID'),
        'client_secret' => env('GOOGLE_OAUTH_KEY'),
        'redirect' => '/google-callback',
    ],

    'outlook' => [
        'client_id' => env('OUTLOOK_OAUTH_ID'),
        'client_secret' => env('OUTLOOK_OAUTH_KEY'),
        // 'redirect' => '/google-callback',
    ],

    'ollama' => [
        'base_url' => env('OLLAMA_BASE_URL', 'http://localhost:11434'),
        'model' => env('OLLAMA_MODEL', 'gpt-oss:120b-cloud'),
    ],

    'epayco' => [
        'public_key' => env('EPAYCO_PUBLIC_KEY'),
        'private_key' => env('EPAYCO_PRIVATE_KEY'),
        'p_key' => env('EPAYCO_P_KEY'),
        'invoice_prefix' => env('PREFIJO_FACTURA', 'INV'),
        // En producción NO definas EPAYCO_TEST (o ponlo en false). Solo true en local/pruebas.
        'test' => filter_var(env('EPAYCO_TEST', false), FILTER_VALIDATE_BOOLEAN),
    ],
];
