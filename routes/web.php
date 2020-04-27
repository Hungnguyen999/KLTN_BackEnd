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
Route::get('/guest/category/topCourse', 'GuestController@getCategoryWithTopCourse');
Route::get('/guest/bot', 'GuestChatBotController@chatBot');



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

    Route::post('/admin/bot/answer', "ChatBotController@insertAnswerBot");
    Route::patch('/admin/bot/answer', "ChatBotController@updateAnswerBot");
    Route::delete('/admin/bot/answer', "ChatBotController@deleteAnswerBot");

    Route::get('/admin/bot/question', "ChatBotController@getQuestionBots");
    Route::post('/admin/bot/question', "ChatBotController@insertQuestionBot");
    Route::patch('/admin/bot/question', "ChatBotController@updateQuestionBot");
    Route::delete('/admin/bot/question', "ChatBotController@deleteQuestionBot");

    Route::get('/admin/bot/message', "ChatBotController@getMessageBots");
    Route::post('/admin/bot/message', "ChatBotController@insertMessageBot");
    Route::patch('/admin/bot/message', "ChatBotController@updateMessageBot");
    Route::delete('/admin/bot/message', "ChatBotController@deleteMessageBot");

    // user role

    Route::get('/user','UserController@getUserInfo');
    Route::patch('/user/edit/password', 'UserController@changePassword');
    Route::post('/user/edit/infor','UserController@editInfor');
    Route::patch('/user/edit/profile', 'UserController@editProfile');


    Route::get('/user/category', 'UserCategoryController@getCategories');
    Route::post('/user/course', 'UserCourseController@insertCourse');
    Route::get('/user/course', 'UserCourseController@getCourses');

    Route::get('/user/lesson', 'UserLessonController@getLessons');
    Route::post('/user/lesson', 'UserLessonController@insertLesson');
    Route::post('/user/lesson/edit', 'UserLessonController@updateLesson');




    ////
    Route::post('/messages', 'UserChatController@message');
    Route::get('/user/message/instructor', 'UserChatController@getMyInstructors');
});



Route::get('/messages', 'UserChatController@getMessage');
