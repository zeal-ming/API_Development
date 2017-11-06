<?php
/**
 * Created by PhpStorm.
 * User: intern
 * Date: 2017/11/6
 * Time: 下午4:23
 */

namespace app\common\lib;

/*
 * 短信验证
 */

use aliyun\apiDemo\SmsDemo;
use think\Cache;

class Message {

    public static function check($phoneNumber){

        //随机数
        $num = rand(1000,10000);

        try {

            $res = SmsDemo::sendSms( '源文科技','SMS_109405011',$phoneNumber,
                array(
                    'code' => $num,
                )
            );

        } catch (ApiException $e){

           return false;
        }

        if($res->Code != 'OK'){

            return false;
        }

        Cache::set($phoneNumber, $num, 0);

        return true;
    }
}