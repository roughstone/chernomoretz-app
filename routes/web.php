<?php

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
    return view('master');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
//Route::get('{path}', 'HomeController@index')->where('path', '([А-я\A-z\d-\/_.]+)?');
Route::get('{path}', function () {
    return view('master');
})->where( 'path', '([А-я\A-z\d-\/_.]+)?' );
