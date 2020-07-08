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

Route::post('qr', 'API\QRController@index')->name('qr.index');

Route::group(['middleware' => ['auth:api']], function() {
    Route::post('qr/show', 'API\QRController@show')->name('qr.show');
    Route::any('qr/image/{module}/{size}/{fileName}', 'API\QRController@image')->name('qr.image');
});

Route::fallback(function(){
    return response()->json(['message' => 'Page not found'], 404);
});
