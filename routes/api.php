<?php

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

//Auth Routes
Route::group([
    'prefix' => 'auth',
    'namespace' => 'App\Http\Controllers\Api\Auth',
    'middleware' => 'api'

], function () {
    Route::post('login', 'AuthController@login')->name('gkc.auth.login');
    Route::post('register', 'AuthController@register')->name('gkc.auth.register');
    Route::post('forgot-password', 'AuthController@forgotPassword')->name('gkc.auth.forgot-password');
    Route::post('reset-password', 'AuthController@resetPassword')->name('gkc.auth.reset-password');
});

//Notes
Route::group([
    'prefix' => 'note',
    'namespace' => 'App\Http\Controllers\Api\Note',
    'middleware' => 'auth:sanctum',
], function () {
    Route::get('', 'NoteController@index')->name('gkc.note.index');
    Route::post('', 'NoteController@store')->name('gkc.note.store');
    Route::get('{id}', 'NoteController@show')->name('gkc.note.show');
    Route::put('{id}', 'NoteController@update')->name('gkc.note.update');
    Route::delete('{id}', 'NoteController@destroy')->name('gkc.note.delete');
    Route::post('sync/{id}' , 'NoteController@syncLabel')->name('gkc.note.addLabel');
});

//Labels
Route::group([
    'prefix' => 'label',
    'namespace' => 'App\Http\Controllers\Api\Label',
    'middleware' => 'auth:sanctum',
], function(){
    Route::get('', 'LabelController@index')->name('gkc.label.index');
    Route::post('', 'LabelController@store')->name('gkc.label.store');
    Route::put('{id}', 'LabelController@update')->name('gkc.label.update');
    Route::delete('{id}', 'LabelController@destroy')->name('gkc.label.destroy');
});
