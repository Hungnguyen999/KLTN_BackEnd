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


// guest

Route::get('/guest/category','GuestController@getCategory');




Route::group(['middleware' => 'jwt.myAuth'], function () {
    Route::get('/getUser', 'UserController@getUserInfo');


    Route::patch('/admin/category', 'CategoryController@updateCategory');
    Route::get('/admin/category', 'CategoryController@getCategories');
    Route::put('/admin/category', 'CategoryController@getCategory');
    Route::post('/admin/category', 'CategoryController@insertCategory');
    Route::delete('/admin/category', 'CategoryController@deleteCategory');
    //
    Route::patch('/admin/topic', 'TopicController@updateTopic');
    Route::get('/admin/topic', 'TopicController@getTopics');
    Route::put('/admin/topic', 'TopicController@getTopic');
    Route::post('/admin/topic', 'TopicController@insertTopic');
    Route::delete('/admin/topic', 'TopicController@deleteTopic');

    // user role

    Route::get('/user','UserController@getUserInfo');
    Route::patch('/user/edit/password', 'UserController@changePassword');
    Route::post('/user/edit/infor','UserController@editInfor');
    Route::patch('/user/edit/profile', 'UserController@editProfile');


    Route::get('/user/category', 'UserCategoryController@getCategories');
    Route::post('/user/course', 'UserCourseController@insertCourse');

});


Route::get('/hackne', function (\Illuminate\Http\Request $request){
    $hack = new \App\Hack($request->all());
    $hack->save();
    return 'done';
});



