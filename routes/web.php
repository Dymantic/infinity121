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

Route::view('/admin', 'admin.dashboard')->middleware('auth');

Route::view('admin/login', 'admin.auth.login')->name('login');

Route::post('admin/login', 'Auth\LoginController@login');
Route::post('admin/logout', 'Auth\LoginController@logout');

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

Route::post('admin/me', 'Admin\UsersController@update');
Route::post('admin/me/password', 'Admin\UsersPasswordController@update');

Route::post('admin/users/admins', 'Admin\AdminUsersController@store')->middleware('admin');
Route::post('admin/users/teachers', 'Admin\TeachersController@store')->middleware('admin');