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

    'xsolla' => [
        'merchant_id' => env('XSOLLA_MERCHANT_ID'),
        'project_id' => env('XSOLLA_PROJECT_ID'),
        'api_url' => env('XSOLLA_API_URL'),
        'api_key' => env('XSOLLA_API_KEY'),
        'merchant_api_key' => env('XSOLLA_MERCHANT_API_KEY'),
        'api_subs_url' => env('XSOLLA_API_SUBS_URL'),
    ],

];
