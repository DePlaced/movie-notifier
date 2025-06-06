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

    'tmdb' => [
        'base_url' => env('TMDB_BASE_URL', 'https://api.themoviedb.org/3'),
        'api_key' => env('TMDB_API_KEY', 'your_api_key_here'),
    ],
    'cron' => [
        'username' => env('CRON_USERNAME'),
        'password' => env('CRON_PASSWORD'),
        'secret' => env('CRON_SECRET'),
    ],
    'slack' => [
        'notifications' => [
            'url' => env('SLACK_WEBHOOK_URL'),
            'signing_secret' => env('SLACK_SIGNING_SECRET'),
        ],
    ],

];
