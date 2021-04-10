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

Route::get('/', function () {    return view('welcome');});

Route::get('/checkup-start', 'CheckupController@index');
Route::get('/checkup-next', 'CheckupController@next');
Route::get('/checkup-result', 'CheckupController@result');
Route::get('/question', 'QuestionController@index');

Route::redirect('/admin', '/admin/users');
Route::get('/admin/login', 'Admin\AdminLoginController@index');
Route::post('/admin/login', 'Admin\AdminLoginController@login');
Route::get('/admin/users', 'Admin\UserController@index')->middleware('admin')->name('admin.users.index');

Route::get('/admin/logout', function () {print 'admin logout';});

Route::get('/grid', 'GridController@index');

Route::get('/users/create',  function () {print 'ok';})->name('users.create');
Route::get('/users',  function () {print 'ok';})->name('users.index');


Route::post('ulogin', 'Auth\UloginController@login');
Route::get('/logout', 'Auth\LoginController@logout')->name('login.logout');
Route::auth();
