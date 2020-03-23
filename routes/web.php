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
    return view('welcome');
});

Route::match(['get', 'post'], '/botman', 'BotManController@handle');
Route::get('/botman/tinker', 'BotManController@tinker');

Route::get('/test', function (\Illuminate\Http\Request $request) {
    return $request->input('test');
});



Route::post('/user', 'UserController@register');
Route::post('/userLogin', 'UserController@login');


Route::get('/admin', 'AdminController@register');
Route::post('/adminLogin', 'AdminController@login');


Route::get('/admin/category', 'CategoryController@getCategories');
Route::put('/admin/category', 'CategoryController@getCategory');
Route::post('/admin/category', 'CategoryController@insertCategory');
Route::patch('/admin/category', 'CategoryController@updateCategory');
Route::delete('/admin/category', 'CategoryController@deleteCategory');

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::get('/getUser', 'UserController@getUserInfo');
});