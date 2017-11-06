<?php
/**
 * Created by PhpStorm.
 * AdminUser: intern
 * Date: 2017/10/31
 * Time: 下午2:34
 */

namespace app\admin\controller;

class Index extends Base {

    public function index(){

        $res = $this->updateAll();

        return $this->fetch();
    }

    public function welcome(){

        return 'welcome to news';
    }

    //发送邮件测试
    public function sentEmail(){

        $message = 'success';
        $title = '铭发起个人邮件';
        $email = '943910611@qq.com';

        \phpMailer\Email::send($email,$title,$message);
    }

    //批量更新测试
    public function updateAll(){

        $data = [
            ['id' => 1, 'status'=>0],
            ['id' => 2, 'status'  => 0]
        ];
        return model('News')->saveAll($data);
    }
}