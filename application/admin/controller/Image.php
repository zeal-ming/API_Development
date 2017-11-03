<?php
/**
 * Created by PhpStorm.
 * AdminUser: intern
 * Date: 2017/10/31
 * Time: 下午2:53
 */

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Image extends Controller{

    public function upload(){

        $file = Request::instance()->file('file');

        $info = $file->move('upload');

        if($info && $info->getPathname()){

            $this->result('/'.$info->getPathname(),'1','success','json');
        }

        $this->result('error');
    }
}