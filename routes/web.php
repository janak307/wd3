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
    return view('welcome');
});
Route::get('/google', function () {
    return Socialite::with('google')->redirect();
});
Route::get('/gallery/', 'GalleryController@show');
Route::get('/gallery/{albumId}', 'GalleryController@show');
Route::get('/gallery/{albumId}/{nextPage}', 'GalleryController@show');

Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback');
Auth::routes();



Route::get('/home', 'HomeController@index')->name('home');
