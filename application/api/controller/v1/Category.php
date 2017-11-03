<?php
/**
 * Created by PhpStorm.
 * User: intern
 * Date: 2017/11/3
 * Time: 上午9:31
 */

namespace app\api\controller\v1;

use think\Controller;

class Category extends Controller{

    public function index(){

        $data = [
            [
                'catId' => 1,
                'catName' => '综艺'
            ],
            [
                'catId' => 2,
                'catName' => '体育'
            ],
            [
                'catId' => 3,
                'catName' => '新闻'
            ]
        ];

        return show('1','success',$data,'500');
    }
}