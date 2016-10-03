<?php

use Illuminate\Http\Request;
use App\Http\Requests\AuthRegisterRequest;

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

// Auth routes.
Route::post('register', 'Auth\AuthController@register');
Route::post('password/reset', 'Auth\AuthController@sendResetLinkEmail');

Route::group(['namespace' => 'Api'], function () {
    Route::get('user', 'UsersController@card')->name('user.card');
    Route::put('user', 'UsersController@modify')->name('user.modify');
    Route::patch('users/{user}/delete', 'UsersController@delete')->name('users.delete');
    Route::patch('users/{user}/restore', 'UsersController@restore')->name('users.restore');
    Route::patch('users/{user}/block', 'UsersController@block')->name('users.block');
    Route::patch('users/{user}/unblock', 'UsersController@unblock')->name('users.unblock');
    Route::resource('users', 'UsersController', ['except' => ['create', 'edit']]);
});

Route::group(['namespace' => 'Api'], function () {
    Route::patch('photos/{photo}/delete', 'PhotosController@delete')->name('photos.delete');
    Route::patch('photos/{photo}/restore', 'PhotosController@restore')->name('photos.restore');
    Route::resource('photos', 'PhotosController', ['except' => ['create', 'edit']]);
});
