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


Route::post('login', 'AuthController@login');
Route::post('signup', 'AuthController@signup');

    Route::group(['middleware' => 'auth:api'], function () {

    	Route::post('/send-message', 'ApiMessageController@send');
		Route::get('/get-message', 'ApiMessageController@get');
		Route::get('get-users', 'ApiMessageController@getUser');

	 Route::get('/logout', 'AuthController@logout');
    Route::get('/user', 'ApiMessageController@user');


    });
