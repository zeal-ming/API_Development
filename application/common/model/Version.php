<?php
/**
 * Created by PhpStorm.
 * User: intern
 * Date: 2017/11/6
 * Time: 上午9:41
 */

namespace app\common\model;

use think\Model;

class Version extends Model{

    public function getNewVersion($app_type){

        $data = [
            'app_type' => $app_type,
        ];

        $order = [
            'id' => 'desc'
        ];

        return $this->where($data)->order($order)->select();
    }
}