<?php

use Illuminate\Support\Facades\Route;

// 管理员
Route::namespace("Custom")->group(function () {
    Route::post('login', "CustomController@login");

    Route::middleware(['auth:admin'])->group(function () {
        Route::get('user', 'CustomController@custom');
        Route::post('logout', 'CustomController@logout');
        //上传文件
        Route::post('file', 'CustomController@file');
    });
});

Route::namespace("Topic")->group(function () {
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('topic_list', 'TopicController@topicList');
        Route::get('topic_detail', 'TopicController@topicDetail');
        Route::post('topic_update', 'TopicController@topicUpdate');
        Route::post('topic_add', 'TopicController@topicAdd');
    });
});

Route::namespace("Post")->group(function () {
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('post_list', 'PostController@postList');
        Route::get('post_detail', 'PostController@postDetail');
        Route::post('post_update', 'PostController@postUpdate');
        Route::post('post_add', 'PostController@postAdd');
    });
});

Route::namespace("Comment")->group(function () {
    Route::middleware(['auth:admin'])->group(function () {
        Route::get('comment_list', 'CommentController@commentList');
        Route::get('comment_detail', 'CommentController@commentDetail');
        Route::post('comment_update', 'CommentController@commentUpdate');
        Route::post('comment_add', 'CommentController@commentAdd');
    });
});
