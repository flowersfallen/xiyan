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
