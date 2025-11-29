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

    'telegram' => [
        'bot_token' => env('SERVICES_TELEGRAM_BOT_TOKEN'),
    ],

    'twilio' => [
        'account_sid' => env('SERVICES_TWILIO_ACCOUNT_SID'),
        'auth_token' => env('SERVICES_TWILIO_AUTH_TOKEN'),
        'phone_number' => env('SERVICES_TWILIO_PHONE_NUMBER'),
    ],

    'whatsapp' => [
        'api_key' => env('SERVICES_WHATSAPP_API_KEY'),
        'phone_number_id' => env('SERVICES_WHATSAPP_PHONE_NUMBER_ID'),
    ],

];
