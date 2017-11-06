<?php
/**
 * Created by PhpStorm.
 * User: intern
 * Date: 2017/11/3
 * Time: 上午9:30
 */

namespace app\api\controller\v1;

use app\api\controller\Base;

class News extends Base {

    public function index(){

        $data = model('News')->all();

        return show(1,'success',$data,400);
    }

    //通过点击前端的栏目,根据前端发送的catid,获取新闻
    public function getNewsByCatId(){

        $catid = input('catid',0,'intval');
        $pageNow = input('pageNow',1,'intval');
        $limit = input('limit', 20, 'intval');

        $res = model('News')->getNewsBycatid($catid,$limit,$pageNow);

        return show(1,'success',$res,500);
    }

    //通过前端的搜索框,发送catname,获取新闻
    public function getNewsByCatNames(){

        $title = input('title');

        $res = model('News')->getNewsByCatName($title);

        return show(1,'success', $res,500);

    }

    //点击某个文章时,返回详情
    public function read(){

        $id = input('id','0','intval');

        $res = model('News')->getNewsById($id);

        return show(1,'success',$res, 500);
    }

}