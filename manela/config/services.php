<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Resend, Postmark, AWS, and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'asaas' => [
        'environment' => env('ASAAS_ENV', 'sandbox'),
        'access_token' => env('ASAAS_ACCESS_TOKEN'),
        'webhook_token' => env('ASAAS_WEBHOOK_TOKEN'),
        'sandbox_url' => env('ASAAS_SANDBOX_URL', 'https://api-sandbox.asaas.com/v3'),
        'production_url' => env('ASAAS_PRODUCTION_URL', 'https://api.asaas.com/v3'),
        'timeout' => env('ASAAS_TIMEOUT', 15),
        'connect_timeout' => env('ASAAS_CONNECT_TIMEOUT', 5),
    ],

];
