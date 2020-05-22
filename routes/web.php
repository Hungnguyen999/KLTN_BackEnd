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





Route::post('/user', 'UserController@register');
Route::post('/userLogin', 'UserController@login');


Route::get('/admin', 'AdminController@register');
Route::post('/adminLogin', 'AdminController@login');


// guest

Route::get('/guest/category','GuestController@getCategory');
Route::get('/guest/category/topCourse', 'GuestController@getCategoryWithTopCourse');
Route::get('/guest/bot', 'GuestChatBotController@chatBot');

Route::get('/guest/search', 'GuestSearchController@getItemsSearch');

Route::get('/user/forgotPassword', 'UserController@forgotPassword');
Route::post('/user/forgotPassword', 'UserController@afterForgotPassword');

Route::get('/customerVerify', function (\Illuminate\Http\Request $request) {
    return $request->all();
})->name('customerVerify');
//hung
//Route::get('/guest/course/getlistcomment','GuestDetailCourseController@getListComment');
//Route::get('/guest/course/gettop5course','GuestDetailCourseController@getTop5CourseByTopic');
//Route::get('/guest/course/getdetailcourse','GuestDetailCourseController@getDetailCourse');
//Route::get('/guest/course/getinfoinstructor','GuestDetailCourseController@getInfoInstructor');


Route::get('/guest/course','GuestDetailCourseController@getDetailCourse');

Route::group(['middleware' => 'jwt.myAuth'], function () {
    Route::get('/getUser', 'UserController@getUserInfo');
    Route::get('/test', function (\Illuminate\Http\Request $request) {
        return 'ahihi';
    });

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
    Route::patch('/user/course', 'UserCourseController@publicOrUnPublicCourse');
    Route::get('/user/course/priceTier', 'UserCourseController@getPriceTier');

    Route::get('/user/lesson', 'UserLessonController@getLessons');
    Route::post('/user/lesson', 'UserLessonController@insertLesson');
    Route::post('/user/lesson/edit', 'UserLessonController@updateLesson');

    Route::get('/user/courseLike', 'UserCourseLikeController@getLikeList');
    Route::post('/user/courseLike', 'UserCourseLikeController@likeOrUnlike');

    Route::get('/user/cart', 'UserCartController@getCarts');
    Route::post('/user/cart', 'UserCartController@addToCart');
    Route::delete('/user/cart', 'UserCartController@deleteCarts');



    Route::get('/user/payment', 'VNPayController@create');
    Route::get('/user/student/courses', 'UserCourseController@studentGetCourses');
    Route::get('/user/student/lesson', 'UserStudentLessonController@getLesson');
    Route::get('/user/student/lesson/comment', 'UserStudentLessonController@getComments');
    Route::post('/user/student/lesson/comment', 'UserStudentLessonController@insertComment');
    Route::delete('/user/student/lesson/comment', 'UserStudentLessonController@deleteComment');

    ////
    Route::post('/messages', 'UserChatController@message');
    Route::get('/user/message/instructor', 'UserChatController@getMyInstructors');

    Route::post('/user/course/insertcomment','GuestDetailCourseController@insertComment');
    Route::post('/user/addtocart','UserCartController@addToCart');
    Route::post('/user/getcart','UserCartController@getCarts');
});



Route::get('/messages', 'UserChatController@getMessage');


Route::get('/redirect/{social}', 'SocialAuthController@redirect');
Route::get('/callback/{social}', 'SocialAuthController@callback');

Route::get('/callbackVPN', 'VNPayController@callback');