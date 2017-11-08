<?php
/**
 * Created by PhpStorm.
 * User: intern
 * Date: 2017/11/7
 * Time: 上午9:05
 */

namespace app\common\lib;

use aliyun\apiDemo\SmsDemo;
use think\Cache;
use think\Exception;
use think\Log;

class Aliyun {

    //单例模式
    /*
     * 私有变量,存储实例
     */

    private static $_instance = null;

    private function __construct()
    {
    }

    public static function getInstance(){

        if(is_null(self::$_instance)){
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function sentSms($phone = 0){

        //随机生成验证码
        $code = rand(1000,9999);

//        try {
//            $res = SmsDemo::sendSms(
//                '源文科技',
//                'SMS_109405011',
//                $phone,
//                Array(
//                    'code' => $code,
//                )
//            );
//
//        } catch (Exception $e) {
//
//            Log::write('aliyun seng message'.$e->getMessage());
//            return false;
//        }
//
//        if($res->Code != 'ok'){
//
//            Log::write('code error'.$res->Code);
//            return false;
//        }

        Cache::set($phone,'1234',600);
        return true;
    }

}