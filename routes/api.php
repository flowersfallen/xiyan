<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'auth'], function () {
    Route::any('login', 'AuthController@login');
    Route::post('refresh', 'AuthController@refresh');

    Route::middleware(['auth:api'])->group(function () {
        Route::get('user', 'AuthController@user');
        Route::post('logout', 'AuthController@logout');
    });
});

Route::group(['prefix' => 'v2'], function () {
    Route::get('topic_list', 'TopicController@topicList');
    Route::get('topic_detail', 'TopicController@topicDetail');

    Route::get('post_list', 'PostController@postList');
    Route::get('post_detail', 'PostController@postDetail');

    Route::get('comment_list', 'CommentController@commentList');
    Route::get('comment_detail', 'CommentController@commentDetail');

    Route::middleware(['auth:api'])->group(function () {
        Route::post('topic_add', 'TopicController@topicAdd');
        Route::post('topic_update', 'TopicController@topicUpdate');

        Route::post('post_add', 'PostController@postAdd');
        Route::post('post_update', 'PostController@postUpdate');

        Route::post('comment_add', 'CommentController@commentAdd');
        Route::post('comment_update', 'CommentController@commentUpdate');

        Route::post('file_upload', 'IndexController@file');
    });
});
