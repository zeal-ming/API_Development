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

/*
 * 授权访问的过这个控制器
 */
class Base extends Controller{

    public $header;

    public function _initialize()
    {

        //获取请求头数据
        $this->header = request()->header();

//        throw new ApiException($header['token'],200);
//        $this->testSign();
//        dump(time());

//        每次进来之前先验证签名
//        $this->checkAuthorize();
//        $this->saveActiveUser();
    }

    /*
     * 验证授权是否合法
     */
    public function checkAuthorize()
    {


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

        //explore分割字符串
        list($t1, $t2) = explode(' ',$time);

        $time = $t2.ceil($t1 * 1000);

        $data = [
            'app_type' => 'ios',
            'version' => 1,
            'did' => 'hello',
            'time' => $time,
        ];

        echo IAuth::setSign($data);
        exit();
    }

    /*
     *通过catid,获取catname并返回
     */
    public function addCateName($data){

        $catIds = model('Category')->all();

        $cat_arr = [];
        foreach ($catIds as $val){
            $cat_arr[$val->id] = $val->name;
        }
        //得到数组['id'=>catname]


        foreach ($data as $val){

            $val['catname'] = $cat_arr[$val['catid']];

        }

        return $data;
    }

    /*
     * 版本验证
     */
    public function checkVersion(){

        $header = request()->header();

        //从库中查找,看看与最新版本是否一致
        $data = model('Version')->getNewVersion($header['app_type'])[0];

        if($data['version_code'] == $header['version']){

            $data['is_update'] = [
                0 => '不更新',
            ];

            return show(1,'success','当前已是最新版本',500);
        }

        if($data['is_force'] == 1){
            $data['is_update'] = [
                2 => '强制更新',
            ];
        }

        $data['is_update'] = [
            1 => '更新',
        ];

        return show(1,'success',$data,500);
    }

    /*
     * 记录用户的登录信息
     */
    public function saveActiveUser(){

        $header = request()->header();

        $data = [
            'version' => $header['version'],
            'app_type' => $header['app_type'],
            'did' => $header['did'],
        ];

         model('ActiveUser')->allowField(true)->save($data);
    }
}