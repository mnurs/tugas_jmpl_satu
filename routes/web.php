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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/auth/redirect', 'App\Http\Controllers\Auth\LoginController@redirectToProvider');
Route::get('/auth/callback', 'App\Http\Controllers\Auth\LoginController@handleProviderCallback');
Route::get('send-mail', [App\Http\Controllers\MailController::class, 'index']);