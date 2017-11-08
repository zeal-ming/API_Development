<?php
/**
 * Created by PhpStorm.
 * User: intern
 * Date: 2017/11/2
 * Time: 上午11:04
 */

namespace app\common\lib;

use think\Cache;

class IAuth
{

    /*
     * 验证签名的合法性
     * @param $data
     * @return boolean
     */
    public static function checkSign($data)
    {

        if (empty($data['sign'])) {
            return false;
        }

        //解密sign
        $signStr = (new Aes())->decrypt($data['sign']);

//        dump($signStr); exit();

        //将字符串转换为数组
        parse_str($signStr, $arr);

        //用did判断是否合法(只有did(设备号)是唯一)
        if (!is_array($arr) || empty($arr['did']) || $arr['did'] != $data['did']) {
            return false;
        }

        //时间戳验证,如果当前时间大于验证的时间加上10分钟,则过时
        if(time() > ceil($arr['time'] / 1000) +config('app.app_sign_time')){
            return false;
        }

        //如果能够获取sign的缓存,说明已经使用sign请求过一次,所以不具备唯一性,无权访问
        if(Cache::get($data['sign'])){
            return false;
        }

        return true;
    }

    /*
     * 加密算法
     */
    public static function setSign($data)
    {

        //加密算法
       //1.排序
        ksort($data);

        //2.拼接
        $aesStr = http_build_query($data);

        //加密
        $aesStr = (new Aes())->encrypt($aesStr);

        return $aesStr;

    }

    /*
     * token
     */

    public static function setAppAccessToken($phone){

        //生成唯一字符串
        //microtime(true)生成当前微妙时间的浮点数
        //使用MD5加密微妙数
        //使用unique生成唯一ID,第二个参数为true,让ID更具唯一性
        //用md5加密唯一ID
        $token  = md5(uniqid(md5(microtime(true),true)));

        //拼接手机号,使用sha1加密
        $token = sha1($token.$phone);

        return $token;
    }

}