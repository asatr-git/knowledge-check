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

// Route::get('/', function () {    return view('welcome');});
Route::get('/', 'HomeController@index');
Route::get('/psw/{psw?}', 'HomeController@index');

Route::get('/psw/{psw}/article/{article_id?}', 'CheckupController@article');

Route::get('/checkup-start', 'CheckupController@index');
Route::get('/checkup-next', 'CheckupController@next');
Route::get('/checkup-result', 'CheckupController@result');
Route::get('/question/psw/{psw?}', 'QuestionController@index');
Route::post('/checkanswers', 'CheckupController@checkanswers');


Route::redirect('/admin', '/admin/users');
Route::get('/admin/login', 'Admin\AdminLoginController@index');
Route::post('/admin/login', 'Admin\AdminLoginController@login');
Route::get('/admin/users', 'Admin\UserController@index')->middleware('admin')->name('admin.users.index');

Route::get('/admin/article', 'Admin\ArticleController@index');
Route::get('/admin/article-getList', 'Admin\ArticleController@getList');
Route::get('/admin/article/{id}', 'Admin\ArticleController@getitem');
Route::delete('/admin/article', 'Admin\ArticleController@delitem');
Route::post('/admin/article', 'Admin\ArticleController@saveitem');
Route::post('/admin/article-body', 'Admin\ArticleController@savebody');

Route::get('/admin/question', 'Admin\QuestionController@index');
Route::get('/admin/question-getList', 'Admin\QuestionController@getList');
Route::get('/admin/question/{id}', 'Admin\QuestionController@getitem');
Route::delete('/admin/question', 'Admin\QuestionController@delitem');
Route::post('/admin/question', 'Admin\QuestionController@saveitem');

Route::get('/admin/answer-getList/{question_id}', 'Admin\AnswerController@getList');
Route::delete('/admin/answer', 'Admin\AnswerController@delitem');
Route::post('/admin/answer', 'Admin\AnswerController@saveitem');

Route::get('/admin/checkup', 'Admin\CheckupController@index');
Route::get('/admin/checkup-getList', 'Admin\CheckupController@getList');
Route::get('/admin/checkup-create/{user_id}', 'Admin\CheckupController@create');


// Route::get('/admin/logout', function () {print 'admin logout';});
Route::get('/admin/logout', 'Auth\LoginController@logout');

Route::get('/grid', 'GridController@index');

Route::get('/users/create',  function () {print 'ok';})->name('users.create');
Route::get('/users',  function () {print 'ok';})->name('users.index');


// Route::post('ulogin', 'Auth\UloginController@login');
// Route::get('/logout', 'Auth\LoginController@logout')->name('login.logout');
// Route::auth();
