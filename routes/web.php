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

Route::get('/','MasterController@index')->name('login');
Route::post('/auth','MasterController@auth');
Route::get('/kuisioner_corona','MasterController@corona');
Route::get('check/employee_id','MasterController@checkEmployeeId');
Route::post('post/form', 'MasterController@postForm');
Route::post('post/reset_password', 'MasterController@resetPassword');
Route::get('/attendance','AttendanceController@index');

Route::get('/welcome_trial', function () {
	return view('welcome_trial');
});
