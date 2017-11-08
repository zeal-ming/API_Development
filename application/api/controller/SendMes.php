<?php
/**
 * Created by PhpStorm.
 * User: intern
 * Date: 2017/11/7
 * Time: 上午9:44
 */

namespace app\api\controller;

use app\common\lib\Aes;
use app\common\lib\Aliyun;

use think\Controller;
use think\Validate;

class SendMes extends Controller{

    public function save(){

        //判断是否是post请求
        if(!$this->request->isPost()){
            return show(0, '请求不合法',[],400);
        }

        //判断号码是否符合规范
        $validate = new Validate(['phone'=>'require|number|length:11']);

        if($validate->check(input('phone'))){
            return show(0, $validate->getError(),[],400);
        }

        //发送验证码
       $res = Aliyun::getInstance()->sentSms(input('phone'));

        if(!$res){
            return show(0, '验证码发送失败',[],400);
        }

        return show(1,'验证码发送成功', [],200);

    }

    //模拟前端发送验证码验证(加密)
    public function sendVerify(){

        $data = [
            'code' => '1234',                   //验证码
            'phone' => '15004116517',               //手机号
            'rand' => rand(100,999)             //随机数
        ];

        //排序
        ksort($data);

        //拼接
        $str = http_build_query($data);

        //加密
        $aesStr = (new Aes())->encrypt($str);

        return $aesStr;
    }



}