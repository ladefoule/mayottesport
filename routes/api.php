<?php
/**
 * @author ALI MOUSSA Moussa <admin@mayottesport.com>
 * @copyright 2020 ALI MOUSSA Moussa
 * @license MIT
 */

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

// Route::apiResource('/{table}', 'CrudAdminController');

Route::get('/journee/render', 'CompetitionController@journeeRender')->name('journee.render');

Route::post('/images', function (Request $request) {
    $images = Storage::allFiles('public/img');
    foreach ($images as $image) {
        $image = str_replace('public/', 'storage/', $image);
        $images_list[] = [
            'title' => $image,
            'value' => asset($image),
        ];
    }

    return $images_list ?? [];
});

// Route::get('/{table}', 'CrudAdminController@index');
// Route::get('/{table}/{id}', 'CrudAdminController@show');
// Route::post('/{table}', 'CrudAdminController@store');
// Route::put('/{table}/{id}', 'CrudAdminController@update');
// Route::delete('/{table}/{id}', 'CrudAdminController@delete');
