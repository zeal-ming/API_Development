<?php
/**
 * Created by PhpStorm.
 * User: intern
 * Date: 2017/11/2
 * Time: 下午3:18
 */

namespace app\api\controller;

use think\Controller;

/*
 * 向前端提供时间接口
 */
class Time extends Controller{

    public function index(){

        return show(1,'success','','200');

    }

}