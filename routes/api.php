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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/journee/calendrier', 'CompetitionController@calendar');

// Route::apiResource('/{table}', 'GestionCrudTableController');

// Route::get('/{table}', 'GestionCrudTableController@index');
// Route::get('/{table}/{id}', 'GestionCrudTableController@show');
// Route::post('/{table}', 'GestionCrudTableController@store');
// Route::put('/{table}/{id}', 'GestionCrudTableController@update');
// Route::delete('/{table}/{id}', 'GestionCrudTableController@delete');
