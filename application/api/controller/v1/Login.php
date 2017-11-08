<?php
/**
 * Created by PhpStorm.
 * User: intern
 * Date: 2017/11/7
 * Time: 下午1:54
 */

namespace app\api\controller\v1;

use app\common\lib\Aes;
use think\Controller;
use app\common\lib\IAuth;
use think\Cache;
use think\Validate;

class Login extends Controller{

    //验证码验证
    public function save(){

        if(!$this->request->isPost()){
            return show(0, '请求不合法',[],400);
        }

        $data = input('post.sign');

        //解密
        $data = $this->explainVerify($data);
        $validate = new Validate(['phone'=>'require|number|length:11','code'=>'require|number|length:4']);

//        return $data;

        if(!$validate->check($data)){
            return show(0,$validate->getError(),[],400);
        }

        //验证验证码是否正确
        if(Cache::get($data['phone']) != $data['code']){

            return show(0,'验证码不正确',[],400);
        }

        $token = IAuth::setAppAccessToken(input('post.phone'));


//        return $data['phone'];

        //判断用户是否是第一次登录,第一次登录就添加一条新用户数据,不是就更新用户数据
        $res = model('User')->get(['phone'=>$data['phone']]);

        //用户不存在,
        if(empty($res)){

            //设置存入数据库
            $condition = [
                'username' => 'zm'.$data['phone'],
                'password' => '123456',
                'token' => $token,
                'phone' => $data['phone'],
                'time_out' => strtotime('+7 days')
            ];

            $user_id = model('User')->save($condition);
        } else {

            //用户存在,更新token,time_out
            $data = [
                'token' => $token,
                'time_out' => strtotime('+7 days'),  //设置有效期
                'status' => 1,
                'update_time' => time(),
            ];

            $user_id = model('User')->save($data,['id'=>$res->id]);
        }

        //给前端返回数据,携带token


        //为了安全性,需要对token进行加密
        $result = [
            'token' => (new Aes())->encrypt($token.'||'.$user_id)
        ];


        return show(1,'登录成功', $result,200);

    }


    //处理前端发过来的验证码
    public function explainVerify($sign){

        if(empty($sign)){
            return show(0,'签名不能为空',[],400);
        }
        //解密
        $str = (new Aes())->decrypt($sign);

        //分解
        parse_str($str,$data);

        return $data;
    }

}