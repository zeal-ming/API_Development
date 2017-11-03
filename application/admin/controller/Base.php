<?php
/**
 * Created by PhpStorm.
 * AdminUser: intern
 * Date: 2017/10/31
 * Time: 下午2:53
 */

namespace app\admin\controller;

use think\Controller;

class Base extends Controller{

    private $accout;            //存储登录用户的账号

    protected function _initialize()
    {

        if(!$this->isLogin()){
            $this->redirect('login/index');
        }

    }

    //检查是否登录
    public function isLogin(){

        if($this->getUser()){

            return true;
        } else {
            return false;
        }

    }

    //获取登录的用户
    public function getUser(){

        if(!$this->accout){

            $this->accout = session('user','', 'ent');
        }
        return $this->accout;
    }

}