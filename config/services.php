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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    'ticket_system' => [
        'support_domain' => env('TICKET_SYSTEM_SUPPORT_DOMAIN'),
        'api_key' => env('TICKET_SYSTEM_API_KEY'),
        'api_secret' => env('TICKET_SYSTEM_API_SECRET'),
        'sso_secret' => env('TICKET_SYSTEM_SSO_SECRET'),
    ],

    'central_payment' => [
        'base_url' => env('CENTRAL_PAYMENT_BASE_URL'),
        'api_key' => env('CENTRAL_PAYMENT_API_KEY'),
        'secret_key' => env('CENTRAL_PAYMENT_SECRET_KEY'),
        'api_version' => env('CENTRAL_PAYMENT_API_VERSION', 'v1'),
        'timeout' => env('CENTRAL_PAYMENT_TIMEOUT', 30),
        'verify_ssl' => env('CENTRAL_PAYMENT_VERIFY_SSL', true),
    ],

    'ocr' => [
        'api_key' => env('OCR_API_KEY'),
        'default_price' => env('OCR_DEFAULT_PRICE'),
        'secure_api_key' => env('OCR_SECURE_API_KEY'),
    ],

    'custom_image_api' => [
        'url' => env('CUSTOM_IMAGE_API_URL'),
        'key' => env('CUSTOM_IMAGE_API_KEY'),
    ],
];
