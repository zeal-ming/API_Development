<?php
/**
 * Created by PhpStorm.
 * User: intern
 * Date: 2017/11/1
 * Time: 下午4:24
 */

namespace app\common\lib;

use think\Exception;
use Throwable;

class ApiException extends Exception{

    //类属性,每个实例对象都有
    public $httpCode = 500;

    public function __construct($message = "", $httpCode=500, $code = 0, Throwable $previous = null)
    {
        $this->httpCode = $httpCode;

        parent::__construct($message, $code, $previous);
    }
}