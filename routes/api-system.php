<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * API System routes
 */


// Used by Sabre/DAV
Route::post('files/access', 'ApiSystem\FileController@access');

// Used by GitLab to trigerr quota recalculation
Route::post('files/quota/recalculate', 'ApiSystem\FileController@quotaRecalculate')
    ->name("sys.files.quota.recalculate");

// Used to check whether user can push
Route::post('files/can-push', 'ApiSystem\FileController@access');

// Used by GitLab to triger quota recalculation
Route::post('gitlab/events/', 'ApiSystem\GitlabController@events')
    ->name("sys.gitlab.events");

// Used by DEV GitLab to download release
Route::post('gitlab-dev/events/', 'ApiSystem\DevGitlabController@events')
    ->name("sys.gitlab-dev.events");

// Used to check whether user can push
Route::get('woocommerce/sync', 'ApiSystem\WoocommerceController@sync');

