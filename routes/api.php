<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login_no_safety_post', 'App\Http\Controllers\API\AuthNoSafetyAPIController@getLoginData');
Route::get('/login_no_safety', 'App\Http\Controllers\API\AuthNoSafetyAPIController@getLoginData');