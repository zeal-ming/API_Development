<?php
/**
 * Created by PhpStorm.
 * AdminUser: intern
 * Date: 2017/10/31
 * Time: 下午3:31
 */

namespace app\common\model;

use think\Model;

class News extends Model {

    //根据查询条件获取数据
    public function getNewsByConditions($condition = []){

        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];

        return $this->where($condition)->order($order)->paginate(3);
    }
}