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
    return view('front.home.page');
});

Route::group(['namespace' => 'Auth'], function() {
    Route::view('admin/login', 'admin.auth.login')->name('login');
    Route::post('admin/login', 'LoginController@login');
    Route::post('admin/logout', 'LoginController@logout');

    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')
         ->name('password.request');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')
         ->name('password.email');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')
         ->name('password.reset');
    Route::post('password/reset', 'ResetPasswordController@reset')
         ->name('password.update');
});



Route::group(['prefix' => 'admin', 'middleware' => ['auth'], 'namespace' => 'Admin'], function() {
    Route::view('/admin', 'admin.dashboard');

    Route::get('users', 'UsersController@index')->middleware('admin');
    Route::post('me', 'UsersController@update');
    Route::post('users/admins', 'AdminUsersController@store')->middleware('admin');
    Route::post('users/teachers', 'TeachersController@store')->middleware('admin');
    Route::post('me/password', 'UsersPasswordController@update');

    Route::get('me/profile', 'ProfilesController@show');
    Route::post('profiles/{profile}', 'ProfilesController@update');
    Route::post('me/profile/image', 'ProfileImageController@update');

    Route::get('subjects', 'SubjectsController@index')->middleware('admin');
    Route::post('subjects', 'SubjectsController@store')->middleware('admin');
    Route::post('subjects/{subject}', 'SubjectsController@update')->middleware('admin');
    Route::post('subjects/{subject}/image', 'SubjectTitleImageController@store');
});

Route::group(['prefix' => 'admin/pages', 'middleware' => ['auth'], 'namespace' => 'Admin\Pages'], function() {
    Route::get('users', 'UsersController@index');
    Route::get('users/{user}', 'UsersController@show');
    Route::get('me/profile', 'ProfilesController@show');
    Route::view('subjects', 'admin.subjects.index');
});









