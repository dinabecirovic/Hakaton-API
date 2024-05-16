<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * Normal Api throttle limit
 * Defined in \app\Providers\RouteServiceProvider.php
 * function configureRateLimiting
 */
Route::middleware(['throttle:api'])->group(function () {

    /**
     * Default route
     */
    Route::get('/', function () {
        return response('OK');
    });

    /**
     * Auth routes
     */
    Route::post('auth/login', 'Auth\LoginController@login');
    Route::post('auth/register', 'Auth\RegisterController@register');
    Route::post('auth/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('auth/password/reset', 'Auth\ResetPasswordController@reset');

    /**
     * User routes
     */
    Route::post('/users/by-uuid', 'Api\UserController@indexByUUID');
    Route::get('/user', 'Api\UserController@show');
    Route::patch('/user', 'Api\UserController@update');
    Route::delete('/user', 'Api\UserController@delete');
    Route::post('/user/avatar', 'Api\UserController@avatar');
    Route::post('/user/password', 'Api\UserController@changePassword');


});

/**
 * Custom throttle middleware for asset download
 * Defined in \app\Providers\RouteServiceProvider.php<
 * function configureRateLimiting
 */
Route::middleware(['throttle:asset-download'])->group(function () {

    /**
     * File art assets
     */
    Route::get('/art-asset/download', 'Api\ArtAssetController@getAssetData');
    Route::post('/art-asset/upload', 'Api\ArtAssetController@uploadAssetData');

    /**
     * File previews
     */
    Route::get('/file/previews', 'Api\FileController@previews');
    Route::get('/file/preview/img', 'Api\FileController@previewImg')
        ->name('file.preview.img');
    Route::post('/file/preview/upload', 'Api\FileController@uploadPreviewData');

    /**
     * Public file previews
     */
    Route::get('/public/file/previews', 'ApiPublic\FileController@previews');
    Route::get('/public/file/preview/img', 'ApiPublic\FileController@previewImg')
        ->name('public.file.preview.img');

});
