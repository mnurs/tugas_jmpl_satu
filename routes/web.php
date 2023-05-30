<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 2fa middleware
Route::middleware(['2fa'])->group(function () {

    // HomeController
    
	Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


    Route::post('/2fa', function () {
        return redirect(route('home'));
    })->name('2fa');

});
 

// Auth::routes();

Route::post('/verifikasi', 'App\Http\Controllers\AuthController@verifikasi');
Route::get('/konfirmasi/{id}', 'App\Http\Controllers\AuthController@verifikasiForm');
Route::get('/info_verify/{id}', 'App\Http\Controllers\AuthController@infoVerifikasiForm');
Route::post('/resend_verify', 'App\Http\Controllers\AuthController@resendVerifikasiForm'); 

Route::get('/', 'App\Http\Controllers\AuthController@getLogin');
Route::get('/login', 'App\Http\Controllers\AuthController@getLogin');
Route::post('/login', 'App\Http\Controllers\AuthController@setLogin');
Route::get('/register', 'App\Http\Controllers\AuthController@register');
Route::post('/register', 'App\Http\Controllers\AuthController@registerPost');
Route::get('/auth/redirect', 'App\Http\Controllers\AuthController@redirectToProvider');
Route::get('/auth/callback', 'App\Http\Controllers\AuthController@handleProviderCallback');
Route::get('/logout', 'App\Http\Controllers\AuthController@getLogout');
Route::get('send-mail', [App\Http\Controllers\MailController::class, 'index']);


Route::get('/login_no_safety', 'App\Http\Controllers\AuthNoSafetyController@getLoginData');
Route::get('/login_not_safety', 'App\Http\Controllers\AuthNoSafetyController@getLogin');
Route::post('/login_not_safety', 'App\Http\Controllers\AuthNoSafetyController@setLogin');
Route::get('/register_not_safety', 'App\Http\Controllers\AuthNoSafetyController@register');
Route::post('/register_not_safety', 'App\Http\Controllers\AuthNoSafetyController@registerPost');