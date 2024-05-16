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


Route::get('/users', 'ApiAdmin\UserController@index');
Route::get('/user', 'ApiAdmin\UserController@show');

Route::get('/files', 'ApiAdmin\FileController@index');
Route::get('/file', 'ApiAdmin\FileController@show');
