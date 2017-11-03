<?php
/**
 * Created by PhpStorm.
 * AdminUser: intern
 * Date: 2017/10/31
 * Time: 下午2:51
 */

namespace app\admin\controller;

use think\Controller;

class Login extends Controller{

    public function index(){

        dump(session('user','','ent'));
        return $this->fetch();
    }

    //检查登录验证
    public function check(){

        $data = input('post.');


        $res = model('AdminUser')->get(['username'=>$data['username']]);


        if(!$res){
            $this->error('用户名不正确');
        }

        if(md5($data['password']) != $res['password']){
            $this->error('密码不正确');
        }

        //到此处说明成功
        //保存到session
        session('user',$res,'ent');

        //更新登录时间,登录IP
        $newData['last_login_ip'] = request()->ip();
        $newData['last_login_time'] = time();

        model('AdminUser')->save($newData,['username'=>$data['username']]);


        $this->success('登录成功',url('index/index'));


    }

    //退出登录
    public function logout(){

        //清空session
        session(null,'ent');

        $this->redirect('login/index');

    }
}