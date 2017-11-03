<?php
/**
 * Created by PhpStorm.
 * User: intern
 * Date: 2017/11/1
 * Time: 下午3:28
 */

namespace app\api\controller;


use app\common\lib\ApiException;
use think\Controller;

class Train extends Controller{

    public function index(){

        $res = model('News')->all();

        throw new ApiException('df',302);

       return show(1,'success',$res,200);
    }

    public function edit($id){

        $res = model('News')->get($id);

        return show(1,'success',$res,200);
    }

    public function read($id){

        $res = model('News')->get($id);

        return show(1,'success',$res,200);
    }

    public function update($id){

        $res = model('News')->get($id);

        return show(1,'success',$res,200);
    }

    public function save(){


    }
}