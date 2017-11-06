<?php
/**
 * Created by PhpStorm.
 * User: intern
 * Date: 2017/11/3
 * Time: 上午10:26
 */

namespace app\api\controller\v1;

use app\api\controller\Base;

class Index extends Base {

    public function index(){

        $lunbo = model('News')->getNewsByHead(3);
        $lunbo = $this->addCateName($lunbo);

        $position = model('News')->getNewsPosition(3);
        $position = $this->addCateName($position);

        //组装数据
        $data = [
            'lunbo' => $lunbo,
            'position' =>  $position,

        ];

        return show(1, 'success',$data, 500);
    }

}