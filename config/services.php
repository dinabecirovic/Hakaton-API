<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'gitlab' => [
        'host' => env('GITLAB_HOST'),
        'http_port' => env('GITLAB_HTTP_PORT', 443),
        'http_secured' => env('GITLAB_HTTP_SECURED', true),
        'ssh_port' => env('GITLAB_SSH_PORT', 22),
        'token' => env('GITLAB_IMPERSONATION_TOKEN', null),
    ],

    'recaptcha_ent' => [
        'project_id' => env('GOOGLE_RECAPTCHA_PROJECT_ID'),
        'api_key' => env('GOOGLE_RECAPTCHA_API_KEY'),
        'site_key' => env('GOOGLE_RECAPTCHA_SITE_KEY'),
    ], 

];
