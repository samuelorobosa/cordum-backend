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


//Auth Routes
Route::group([
    'prefix' => 'auth',
    'namespace' => 'App\Http\Controllers\Api\Auth',

], function () {
    Route::post('login', 'AuthController@login')->name('gkc.auth.login');
    Route::post('register', 'AuthController@register')->name('gkc.auth.register');
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
//    Route::get('labels/{id}', 'NoteController@labels')->name('gkc.note.delete');
});

//Labels
Route::group([
    'prefix' => 'label',
    'namespace' => 'App\Http\Controllers\Api\Label',
    'middleware' => 'auth:sanctum',
], function(){
    Route::get('', 'LabelController@index')->name('gkc.label.index');
});
