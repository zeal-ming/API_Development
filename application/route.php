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

Route::resource('Train','api/Train');


Route::get('time','api/time/index');
Route::get('check', 'api/base/checkSign');

//针对API的版本升级
Route::resource(':ver/category','api/:ver.category');

Route::resource('news','api/news');




