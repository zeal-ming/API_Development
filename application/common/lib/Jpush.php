<?php
/**
 * Created by PhpStorm.
 * User: intern
 * Date: 2017/11/6
 * Time: 下午2:42
 */

namespace app\common\lib;

use jpush\src\JPush\Client;

class Jpush {

    public static function push($content, $newId){

        $appKey = '75be4ab01fa796a2b88f98d7';
        $masterSecret = '95772655230f869a70777da7';

        $client = new Client($appKey,$masterSecret);

        $data = $client->push()
            ->setPlatform('all')  // 推送平台
            ->addAllAudience()   //广播
            ->setNotificationAlert($content) //推送内容
            ->iosNotification('zm'.[
                    'news_id' => $newId
                ])
            ->androidNotification('zm',[
                'news_id' => 2
            ])
            ->send();
    }
}