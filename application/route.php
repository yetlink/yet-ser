<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

// 检查短地址
Route::get('url/:url', 'api/v1.Url/jump');

// 创建用户
Route::get('api/:version/login', 'api/:version.Yet/login');

// 创建短地址
Route::post('api/:version/create', 'api/:version.Yet/createLink');

// 修改短地址
Route::post('api/:version/modify', 'api/:version.Yet/modifyLink');

// 还原短地址
Route::get('api/:version/code', 'api/:version.Yet/getLongUrl');

// 解析短地址
Route::get('api/:version/link', 'api/:version.Yet/getByPassword');

// 获取用户短地址
Route::get('api/:version/list', 'api/:version.Yet/getLinkList');

// 获取短地址统计数据
Route::get('api/:version/record', 'api/:version.Yet/getLinkRecord');

