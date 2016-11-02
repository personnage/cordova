<?php

/**
 * This is group routes services admin dashboard.
 * Middleware "Employee" must be included to any a controller.
 *
 * Note: This group have shared-base "auth" middleware across any request.
 */
Route::get('/', 'DashboardController@index');

// remove impersonate session
Route::get('impersonation', 'ImpersonationsController@destroy');

Route::patch('user/{user}/delete', 'UserController@delete');
Route::patch('user/{user}/restore', 'UserController@restore');
Route::patch('user/{user}/confirm', 'UserController@confirm');
Route::get('user/{user}/impersonate', 'UserController@impersonate');
Route::resource('user', 'UserController');

Route::patch('role/{role}/delete', 'RoleController@delete');
Route::patch('role/{role}/restore', 'RoleController@restore');
Route::resource('role', 'RoleController');

Route::patch('permission/{permission}/delete', 'PermissionController@delete');
Route::patch('permission/{permission}/restore', 'PermissionController@restore');
Route::resource('permission', 'PermissionController');

Route::group(['prefix' => 'photos', 'namespace' => 'Photos'], function () {
    Route::resource('category', 'CategoryController');

    Route::get('flickr', 'FlickrController@index');
    Route::get('flickr/search', 'FlickrController@search');

    // Route::resource('teleport', 'TeleportController');
});

// dev mode
Route::get('help', 'HelpController@index');



// News group.
Route::group(['prefix' => 'news'], function () {
    Route::patch('{news}/up', 'NewsController@up');
    Route::patch('{news}/down', 'NewsController@down');
    Route::patch('{news}/delete', 'NewsController@delete');
    Route::patch('{news}/restore', 'NewsController@restore');

    Route::group(['prefix' => 'category'], function () {
        Route::patch('{category}/delete', 'NewsCategoryController@delete');
        Route::patch('{category}/restore', 'NewsCategoryController@restore');
    });
    Route::resource('category', 'NewsCategoryController');
});
Route::resource('news', 'NewsController');
// -- News group.

// Route::group(['prefix' => 'specials'], function () {
//     Route::resource('/', 'SpecialsController');
//     Route::resource('category', 'SpecialsCategoryController');
// });


//
