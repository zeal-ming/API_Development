<?php
/**
 * Created by PhpStorm.
 * User: intern
 * Date: 2017/11/1
 * Time: 下午1:46
 */

namespace app\api\controller;

use aliyun\apiDemo\SmsDemo;
use app\common\lib\Aliyun;
use app\common\lib\Jpush;
use app\common\lib\Message;
use think\Cache;

class Test extends Base {

    public function index(){

//        return show('1','可以访问',[],500);
//        $this->testSign();
//        $this->checkSign();
    }

    public function show(){
        echo 'show,post';
    }

    public function create(){
        echo 'create';
    }

    public function read($id){
        echo 'read'.$id;
    }

    public function edit($id){
        echo 'edit'.$id;
    }

    public function update($id){

        echo 'update'.$id;
    }

    public function delete($id){
        echo 'delete'.$id;
    }

    public function push(){

       Jpush::push('hahaf',2);

    }

    public function sendMessage(){

        if(request()->isPost()){

//            $res = Message::check('15004116517');

            $res = Aliyun::getInstance()->sentSms('15004116517');
            if($res === true){
                return show(1,'sss','成功',200);
            }

            Cache::set('15004116517','1234',0);

            return show(1,'sss','失败',200);
        }
    }

    //验证验证码
    public function VerifyMessage(){

        //验证短信回来的验证码
        if(request()->isPost()){

            $postData = input('post.');
            //验证手机号是否合法

            if($postData['number'] != Cache::get($postData['phone'])){
                return show(0,'error',[],400);
            }

            return show(1,'success','登录成功',500);

        }

    }

}