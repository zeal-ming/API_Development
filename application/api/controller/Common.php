<?php
/**
 * Created by PhpStorm.
 * User: intern
 * Date: 2017/11/7
 * Time: 下午3:11
 */

namespace app\api\controller;

use app\common\lib\Aes;
use app\common\lib\ApiException;
use think\Exception;

class Common extends Base {

    public $user;

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub

        if(!$this->isLogin()){
           throw new ApiException('请先登录',400);
        }

    }

    /*
     * 验证用户是否登录
     */

    public function isLogin(){

        $token = $this->header['token'];

        //对token进行基本的验证
        if(empty($token)){
            return false;
        }

        //对token进行解密
        $str = (new Aes())->decrypt($token);

//        return $str;
        //获取真实token
        try {
            $data = explode('||',$str);
            $str = $data[0];

//            return $str;
        } catch (Exception $e){
            return false;
        }

        //根据token从数据库中获取用户
        $res = model('User')->get(['token'=>$str]);

        //如果为空
        if(!$res) return false;

//        throw new ApiException($res->id,209);

        //如果超过时间,则过期
        if($res->time_out < time()) return false;

        $this->user = $res;
        return true;
    }
}