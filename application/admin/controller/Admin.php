<?php
/**
 * Created by PhpStorm.
 * AdminUser: intern
 * Date: 2017/10/31
 * Time: 下午2:50
 */

namespace app\admin\controller;

use think\Controller;

class Admin extends Controller{

    public function add(){

        if(request()->isPost()){

            $data = input('post.');

            //数据校验...

            $res = model('AdminUser')->get(['username'=>$data['username']]);

            //密码过滤(md5);
            $data['password'] = md5($data['password']);

            //获取IP
            $data['last_login_ip'] = $this->request->ip();

            if($res){
               $this->error('该用户已存在');
            }

            //数据入库
            $res = model('AdminUser')->save($data);

            if(!$res){
                $this->error('添加失败');
            }

            $this->success('添加成功');

        }

        return $this->fetch();
    }

    public function check(){



    }
}