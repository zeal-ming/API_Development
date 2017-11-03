<?php
/**
 * Created by PhpStorm.
 * User: intern
 * Date: 2017/11/2
 * Time: 上午10:23
 */

return [
    'aes_key' => '123456789asdfghj',  //秘钥
    'app_type' => [                     //请求设备类型
        'ios',
        'android',
    ],
    'app_sign_time' => 600,          //签名时间戳
    'app_sign_cache_time' => 700        //sign缓存的有效时间

];