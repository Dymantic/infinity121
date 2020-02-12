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
Route::group(['prefix' => LaravelLocalization::setLocale(), 'middleware' => ['localize', 'localizationRedirect', 'localeSessionRedirect'] ], function()
{
    Route::get('/', 'HomepageController@show');
    Route::get('about-us', 'AboutPageController@show');

    Route::get('courses', 'CoursesController@index');
    Route::get('courses/{slug}', 'CoursesController@show');

    Route::get('teachers', 'TeachersController@index');
    Route::get('teachers/{slug}', 'TeachersController@show');

    Route::get('students/sign-up', 'StudentsInquiryController@show');
    Route::get('teachers/sign-up', 'TeachersInquiryController@show');

    Route::get('contact-us', 'ContactMessageController@create');

    Route::get('affiliates', 'AffiliatesController@index');

});

Route::post('students/sign-up', 'StudentsInquiryController@store');
Route::post('teachers/sign-up', 'TeachersInquiryController@store');

Route::post('contact', 'ContactMessageController@store');


Route::get('style-guide', function () {
    return view('front.style-guide', ['light' => true]);
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



Route::group(['prefix' => 'admin/api', 'middleware' => ['auth'], 'namespace' => 'Admin'], function() {
    Route::get('users', 'UsersController@index')->middleware('admin');
    Route::get('me', 'UsersController@show');
    Route::post('me', 'UsersController@update');
    Route::post('users/admins', 'AdminUsersController@store')->middleware('admin');
    Route::post('users/teachers', 'TeachersController@store')->middleware('admin');
    Route::post('me/password', 'UsersPasswordController@update');

    Route::get('me/profile', 'ProfilesController@show');
    Route::post('profiles/{profile}', 'ProfilesController@update');
    Route::post('me/profile/image', 'ProfileImageController@update');

    Route::get('profiles', 'TeacherProfilesController@index');

    Route::post('published-profiles', 'PublishedProfilesController@store')->middleware('admin');
    Route::delete('published-profiles/{profile}', 'PublishedProfilesController@destroy')->middleware('admin');

    Route::post('profiles/{profile}/subjects', 'ProfileSubjectsController@update');

    Route::get('subjects', 'SubjectsController@index')->middleware('admin');
    Route::post('subjects', 'SubjectsController@store')->middleware('admin');
    Route::post('subjects/{subject}', 'SubjectsController@update')->middleware('admin');
    Route::delete('subjects/{subject}', 'SubjectsController@delete')->middleware('admin');
    Route::post('subjects/{subject}/image', 'SubjectTitleImageController@store');

    Route::post('public-subjects', 'PublicSubjectsController@store');
    Route::delete('public-subjects/{subject}', 'PublicSubjectsController@destroy');

    Route::get('nationalities', 'NationalitiesController@index');

    Route::get('affiliates', 'AffiliatesController@index');
    Route::get('affiliates/{affiliate}', 'AffiliatesController@show');
    Route::post('affiliates', 'AffiliatesController@store')->middleware('admin');
    Route::post('affiliates/{affiliate}', 'AffiliatesController@update')->middleware('admin');
    Route::delete('affiliates/{affiliate}', 'AffiliatesController@delete')->middleware('admin');

    Route::post('affiliates/{affiliate}/image', 'AffiliateImagesController@store');

    Route::post('published-affiliates', 'PublishedAffiliatesController@store');
    Route::delete('published-affiliates/{affiliate}', 'PublishedAffiliatesController@destroy');
});

Route::get('admin/dashboard', 'Admin\Pages\DashboardController')->middleware('auth');













