<?php

declare(strict_types=1);

/*
 * This file is part of Laravel GitLab.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
    */

    'default' => 'main',

    /*
    |--------------------------------------------------------------------------
    | GitLab Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Example
    | configuration has been included, but you may add as many connections as
    | you would like. Note that the 3 supported authentication methods are:
    | "none", "oauth" and "token".
    |
    */

    'connections' => [

        'main' => [
            'method'  => 'token',
            'url'     => env('GITLAB_URL'),
            'token'   => env('GITLAB_IMPERSONATION_TOKEN', null),
        ],

        'dev' => [
            'method'                    => 'token',
            'url'                       => env('GITLAB_DEV_URL'),
            'token-macos-installer'     => env('GITLAB_DEV_IMPERSONATION_TOKEN_MAC', null),
            'token-win-installer'       => env('GITLAB_DEV_IMPERSONATION_TOKEN_WIN', null),
            'token-wp-integration'      => env('GITLAB_DEV_IMPERSONATION_TOKEN_WP', null),
        ],

    ],




];
