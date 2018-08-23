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

Route::get('login', 'AuthController@login');
Route::get('client/start/oauth', 'AuthController@clientStartOauth');

// 需要授权才能访问的接口
Route::group(['middleware' => 'auth:api'], function () {
    // 获取用户信息
    Route::get('user/info', 'UserController@info');

    // 获取任务
    Route::resource('tasks', 'TaskController');

});
