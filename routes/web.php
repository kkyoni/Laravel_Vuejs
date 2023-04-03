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

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'preventBackHistory'], function () {
	Route::get('admin', 'Admin\Auth\LoginController@showLoginForm')->name('admin.showLoginForm');
	Route::get('admin/login', 'Admin\Auth\LoginController@showLoginForm')->name('admin.login');
	Route::post('admin/login', 'Admin\Auth\LoginController@login');
	Route::group(['prefix' => 'admin', 'middleware' => 'Admin', 'namespace' => 'Admin', 'as' => 'admin.'], function () {
		Route::get('/sms', 'SmsController@sms')->name('sms');
		Route::get('/dashboard', 'MainController@dashboard')->name('dashboard');
		Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
		// 	//====================> User Management <====================
		Route::get('/user', 'UsersController@index')->name('user.index');
		Route::get('/user/create', 'UsersController@create')->name('user.create');
		Route::post('/user/store', 'UsersController@store')->name('user.store');
		Route::get('/user/edit/{id}', 'UsersController@edit')->name('user.edit');
		Route::post('/user/update/{id}', 'UsersController@update')->name('user.update');
		Route::post('/user/delete/{id}', 'UsersController@delete')->name('user.delete');
		Route::get('/user/show', 'UsersController@show')->name('user.show');
		Route::post('/user/change_status', 'UsersController@change_status')->name('user.change_status');
	});
	Route::get('/{any}', function () {
		return view('welcome');
	})->where('any', '.*');
});
