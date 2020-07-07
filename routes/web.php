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
    Route::view('founder', 'front.about.profiles.founder');

    Route::get('courses', 'CoursesController@index');
    Route::get('courses/{slug}', 'CoursesController@show');

    Route::get('teachers', 'TeachersController@index');
    Route::get('teachers/{slug}', 'TeachersController@show');

    Route::get('students/sign-up', 'StudentsInquiryController@show');
    Route::get('join-us', 'TeachersInquiryController@show');

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
    Route::redirect('admin', '/admin/dashboard');
    Route::redirect('home', '/admin/dashboard');
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
    Route::delete('users/{user}', 'UsersController@destroy')->middleware('admin');

    Route::post('admin-email-subscriptions', 'AdminEmailSubscriptionsController@store')->middleware('admin');
    Route::delete('admin-email-subscriptions/{user}', 'AdminEmailSubscriptionsController@destroy')->middleware('admin');

    Route::get('me', 'UsersController@show');
    Route::post('me', 'UsersController@update');
    Route::post('users/admins', 'AdminUsersController@store')->middleware('admin');
    Route::post('users/teachers', 'TeachersController@store')->middleware('admin');
    Route::post('me/password', 'UsersPasswordController@update');

    Route::get('me/profile', 'MyProfileController@show');
    Route::get('profiles/{profile}', 'ProfilesController@show');
    Route::post('profiles/{profile}', 'ProfilesController@update');
    Route::post('me/profile/image', 'ProfileImageController@update');

    Route::get('me/current-schedule', 'MyCurrentScheduleController@show');

    Route::get('me/available-periods', 'TeacherAvailablePeriodsController@show');
    Route::post('me/available-periods', 'TeacherAvailablePeriodsController@store');

    Route::get('me/unavailable-periods', 'TeacherUnavailablePeriodsController@index');
    Route::post('me/unavailable-periods', 'TeacherUnavailablePeriodsController@store');
    Route::post('me/unavailable-periods/{period}', 'TeacherUnavailablePeriodsController@update');
    Route::delete('me/unavailable-periods/{period}', 'TeacherUnavailablePeriodsController@delete');

    Route::post('me/working-areas', 'TeacherWorkingAreasController@store');

    Route::get('me/due-lessons', 'MyDueLessonsController@index');
    Route::get('me/completed-lessons', 'MyCompletedLessonsController@index');

    Route::get('profiles', 'TeacherProfilesController@index');

    Route::post('profiles-order', 'ProfilesOrderController@store')->middleware('admin');

    Route::post('published-profiles', 'PublishedProfilesController@store')->middleware('admin');
    Route::delete('published-profiles/{profile}', 'PublishedProfilesController@destroy')->middleware('admin');

    Route::post('profiles/{profile}/subjects', 'ProfileSubjectsController@update')->middleware('admin');

    Route::get('subjects', 'SubjectsController@index')->middleware('admin');
    Route::post('subjects', 'SubjectsController@store')->middleware('admin');
    Route::post('subjects/{subject}', 'SubjectsController@update')->middleware('admin');
    Route::delete('subjects/{subject}', 'SubjectsController@delete')->middleware('admin');
    Route::post('subjects/{subject}/image', 'SubjectTitleImageController@store')->middleware('admin');

    Route::post('public-subjects', 'PublicSubjectsController@store')->middleware('admin');
    Route::delete('public-subjects/{subject}', 'PublicSubjectsController@destroy')->middleware('admin');

    Route::post('subjects-order', 'SubjectsOrderController@store')->middleware('admin');

    Route::get('nationalities', 'NationalitiesController@index');

    Route::get('affiliates', 'AffiliatesController@index');
    Route::get('affiliates/{affiliate}', 'AffiliatesController@show');
    Route::post('affiliates', 'AffiliatesController@store')->middleware('admin');
    Route::post('affiliates/{affiliate}', 'AffiliatesController@update')->middleware('admin');
    Route::delete('affiliates/{affiliate}', 'AffiliatesController@delete')->middleware('admin');

    Route::post('affiliates/{affiliate}/image', 'AffiliateImagesController@store')->middleware('admin');

    Route::post('published-affiliates', 'PublishedAffiliatesController@store')->middleware('admin');
    Route::delete('published-affiliates/{affiliate}', 'PublishedAffiliatesController@destroy')->middleware('admin');

    Route::get('countries', 'CountriesController@index');
    Route::post('countries', 'CountriesController@store')->middleware('admin');
    Route::post('countries/{country}', 'CountriesController@update')->middleware('admin');
    Route::delete('countries/{country}', 'CountriesController@delete')->middleware('admin');

    Route::post('countries/{country}/regions', 'RegionsController@store')->middleware('admin');
    Route::post('regions/{region}', 'RegionsController@update')->middleware('admin');
    Route::delete('regions/{region}', 'RegionsController@delete')->middleware('admin');

    Route::post('regions/{region}/areas', 'AreasController@store')->middleware('admin');
    Route::post('areas/{area}', 'AreasController@update')->middleware('admin');
    Route::delete('areas/{area}', 'AreasController@delete')->middleware('admin');


    Route::get('customers', 'CustomersController@index');
    Route::post('customers', 'CustomersController@store')->middleware('admin');
    Route::get('customers/{customer}', 'CustomersController@show');
    Route::post('customers/{customer}', 'CustomersController@update')->middleware('admin');
    Route::delete('customers/{customer}', 'CustomersController@delete')->middleware('admin');
    Route::get('customers/{customer}/courses', 'CustomerCoursesController@index');
    Route::post('customers/{customer}/courses', 'CustomerCoursesController@store')->middleware('admin');

    Route::get('active-courses', 'ActiveCoursesController@index');
    Route::get('courses/{course}', 'CustomerCoursesController@show');
    Route::post('courses/{course}', 'CustomerCoursesController@update')->middleware('admin');
    Route::post('courses/{course}/lesson-blocks', 'CourseLessonBlocksController@store')->middleware('admin');

    Route::post('confirmed-courses', 'ConfirmedCoursesController@store')->middleware('admin');

    Route::post('courses/{course}/location', 'CourseLocationController@store')->middleware('admin');
    Route::post('courses/{course}/teacher', 'CourseTeacherController@store')->middleware('admin');
    Route::delete('courses/{course}/teacher', 'CourseTeacherController@destroy')->middleware('admin');

    Route::post('available-teachers', 'AvailableTeachersController@show')->middleware('admin');

    Route::get('logged-lessons', 'LoggedLessonsController@index')->middleware('admin');
    Route::get('due-logging-lessons', 'DueLoggingLessonsController@index')->middleware('admin');

    Route::post('lessons/{lesson}/log', 'LessonsLogController@store');
    Route::post('cancelled-lessons', 'CancelledLessonsController@store');
});

Route::get('admin/dashboard', 'Admin\Pages\DashboardController')->middleware('auth');













