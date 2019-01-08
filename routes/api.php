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
//Route::apiResources(['Sliders' => 'API\SlidersController']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/Sliders', 'API\SlidersController@index');
Route::post('/Sliders', 'API\SlidersController@store')->middleware('auth:api');
Route::patch('/Sliders/{Slider}', 'API\SlidersController@update')->middleware('auth:api');
Route::delete('/Sliders/{Slider}', 'API\SlidersController@destroy')->middleware('auth:api');

Route::get('/moreNews', 'API\NewsController@moreNewsIndex');
Route::get('/news', 'API\NewsController@index');
Route::post('/news', 'API\NewsController@store')->middleware('auth:api');
Route::patch('/news/{news}', 'API\NewsController@update')->middleware('auth:api');
Route::delete('/news/{news}', 'API\NewsController@destroy')->middleware('auth:api');

Route::get('/coachs', 'API\CoachController@index');
Route::post('/coachs', 'API\CoachController@store')->middleware('auth:api');
Route::patch('/coachs/{coach}', 'API\CoachController@update')->middleware('auth:api');
Route::delete('/coachs/{coach}', 'API\CoachController@destroy')->middleware('auth:api');
