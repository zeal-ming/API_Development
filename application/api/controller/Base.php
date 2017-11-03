<?php
/**
 * Created by PhpStorm.
 * User: intern
 * Date: 2017/11/2
 * Time: 上午10:39
 */

namespace app\api\controller;

use app\common\lib\IAuth;
use app\common\lib\ApiException;
use think\Cache;
use think\Controller;

class Base extends Controller{

    public function _initialize()
    {
        $this->testSign();
//        dump(time());

//        $this->checkAuthorize();


    }

    /*
     * 验证授权是否合法
     */
    public function checkAuthorize()
    {

//        dump(show('1','可以访问',[],200));
//        exit();

        //获取请求头数据
        $header = request()->header();

//        dump($header);exit();

        //api文档规定 请求头必须带 app_type参数,并且值为iOS或Android
        //基础参数的校验
        //校验app_type
        if(empty($header['app_type']) || !in_array($header['app_type'],config('app.app_type'))){
            throw new ApiException('app_type error',400);
        }

        //校验签名
        if(!IAuth::checkSign($header)) {
            throw new ApiException('无权访问',401);
        }

        //设置sign字符串的缓存
        Cache::set($header['sign'],1, config('app.app_sign_cache_time'));


        dump('可以访问');

    }

    /*
     * 模拟前端加密
     */
    public function testSign(){

        $time = microtime();  //获取时间
//        dump($time);

        //explore分割字符串
        list($t1, $t2) = explode(' ',$time);

        $time = $t2.ceil($t1 * 1000);

//        dump($time); exit();
        $data = [
            'app_type' => 'ios',
            'version' => 1,
            'did' => 'hello',
            'time' => $time,
        ];

        echo IAuth::setSign($data);
        exit();
    }

}