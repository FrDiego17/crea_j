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

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'clerk' => [
    'secret_key' => env('CLERK_SECRET_KEY'),
    'publishable_key' => env('CLERK_PUBLISHABLE_KEY'),
    'api_url' => env('CLERK_API_URL', 'https://api.clerk.dev/v1'),
    ],

    'google' => [
        'maps_api_key' => env('GOOGLE_MAPS_API_KEY', 'AIzaSyAXWZfin7I7WXH62ZnHV-TRoC2XtMkMHDo'),
        'places_api_key' => env('GOOGLE_PLACES_API_KEY', null), // Opcional si usas una key diferente
        'geocoding_api_key' => env('GOOGLE_GEOCODING_API_KEY', null), // Opcional si usas una key diferente
    ],

];
