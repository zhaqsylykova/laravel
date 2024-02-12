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

    /*'sms' => [
        'provider' => env('SMS_PROVIDER'),
        'username' => env('SMS_USERNAME'),
        'password' => env('SMS_PASSWORD'),
        // Другие параметры

    ],*/
    'twilio' => [
        'sid' => env('AC065260f25eeada6f243e6b931c7b881c'),
        'token' => env('55f332d425f5eabc080361630bc7b27d'),
        'from' => env('+18556993563'),
        'ssl_cert' => 'C:\Users\Professional\PhpstormProjects\laravel\cacert.pem',

    ],

    //'ssl_cert' => 'C:\Users\Professional\PhpstormProjects\laravel\cacert.pem',



];
