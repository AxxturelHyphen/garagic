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

    // ia del oraculo: cualquier api compatible con openai (groq, openrouter...)
    // para cambiar de proveedor solo hay que cambiar estos valores en .env
    'ai' => [
        // clave secreta del proveedor, nunca va al codigo ni a git
        'key' => env('AI_API_KEY'),
        // direccion base de la api, sin la ruta final /chat/completions
        'base_url' => env('AI_BASE_URL', 'https://api.groq.com/openai/v1'),
        // modelo a usar, se deja en .env porque los proveedores los retiran
        'model' => env('AI_MODEL', 'openai/gpt-oss-20b'),
        // temperatura baja para que responda con precision y no invente averias
        'temperature' => env('AI_TEMPERATURE', 0.3),
        // segundos maximos de espera antes de dar la ia por caida
        'timeout' => env('AI_TIMEOUT', 20),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
