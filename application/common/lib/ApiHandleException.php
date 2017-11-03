<?php
/**
 * Created by PhpStorm.
 * User: intern
 * Date: 2017/11/1
 * Time: 下午4:11
 */

namespace  app\common\lib;

use think\Exception;

use think\exception\Handle;

class ApiHandleException extends Handle{

    public $httpCode = 500;

    //重写父类中的render函数
    public function render(Exception $e){


        if(config('app_debug') == true){

            parent::render($e);
        }

        if($e instanceof ApiException){
            $this->httpCode = $e->httpCode;
        }


        return show(0, $e->getMessage(),[],$this->httpCode);
    }
}