<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
// Authentication With Socialite
Route::group(['prefix' => 'auth/{provider}', 'namespace' => 'Auth'], function () {
    Route::get('/', 'SocialiteController@redirectToProvider');
    Route::get('callback', 'SocialiteController@handleProviderCallback');
});
// Std authentication
Auth::routes();

Route::get('/', function () {
    return view('welcome');
})->middleware('App\Http\Middleware\RedirectIfAuthenticated');

Route::get('home', 'FlickrController@index');
Route::get('flickr', 'FlickrController@photos');

Route::get('google', function (\App\Repositories\GoogleGeocodingRepositories $repo) {
    // $result = $repo->findByPoint('55.790465', '37.589542'); Moscow
    // return $repo->findByPoint('36.118561', '-115.187090');
    return $repo->findByPoint('36.118561', '-64.81291');

    dd($result);
});
