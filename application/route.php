<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

Route::resource('test','api/test');

//Route::rule('test','api/test/index','GET');

//Route::rule('test','api/test/show','POST');

//Route::resource('Train','api/Train');

//Route::get('time','api/time/index');
//Route::get('check', 'api/base/checkSign');
////Route::get('search','api/news/getNewsByCatNames');
//
////针对API的版本升级
Route::resource(':ver/category','api/:ver.category');
//Route::resource(':ver/index','api/:ver.index');
Route::resource(':ver/news','api/:ver.news');

//Route::resource('version','api/base');

Route::get('version', 'api/base/checkVersion');

Route::get('saveActiveUser','api/base/saveActiveUser');

Route::post('sendMessage','api/test/sendMessage');

Route::post('VerifyMessage','api/test/VerifyMessage');