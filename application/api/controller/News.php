<?php
/**
 * Created by PhpStorm.
 * User: intern
 * Date: 2017/11/3
 * Time: 上午9:30
 */

namespace app\api\controller;

use think\Controller;

class News extends Controller {

    public function index(){

        $data = model('News')->all();

        return show(1,'success',$data,400);

    }

}