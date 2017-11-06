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

    //根据前端要求的数量获取is_head_figure =1 的数据
    public function getNewsByHead($limit = 4){

        $data = [
            'is_head_figure' => 1,
            'status' => 1
        ];

        $order = [
            'listorder' => 'desc',
            'id' => 'desc',
        ];

        return $this->where($data)
            ->field($this->getField())
            ->order($order)
            ->limit($limit)
            ->select();
    }

    //根据前端要求的数量,获取前端is_position = 1 的数据
    public function getNewsPosition($limit = 20){

        $data = [
            'is_position' => 1,
            'status' => 1
        ];

        $order = [
            'listorder' => 'desc',
            'id' => 'desc',
        ];

        return $this->where($data)
            ->order($order)
            ->field($this->getField())
            ->limit($limit)
            ->select();
    }

    //根据栏目ID返回新闻
    public function getNewsByCatId($catid, $limit, $pageNow){

        $data = [
            'status' => 1,
            'catid' => $catid
        ];

        $order = [
            'listorder'=> 'desc',
            'id' => 'desc',
        ];

        return $this->where($data)
            ->order($order)
            ->limit($limit)
            ->field($this->getField())
            ->select();
    }

    //根据搜索框,返回新闻
    public function getNewsByCatName($title, $limit=5, $pageNow=0){

        $data = [
            'title' => ['like', '%'.$title.'%'],
            'status' => 1
        ];

        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];

        return $this->where($data)
            ->order($order)
            ->limit($pageNow * $limit,$limit)
            ->field($this->getField())
            ->select();
    }

    //根据ID,返回某个文章的所有字段
    public function getNewsById($id){

        $data = [
            'id' => $id,
            'status' => 1
        ];

        $order = [
            'listorder'=> 'desc',
            'id' => 'desc'
        ];

        $this->where($data)->setInc('read_count');

        return $this->where($data)
            ->order($order)
            ->select();
    }

    //返回常用字段
    public function getField(){

        return [
            'id',
            'title',
            'image',
            'catid',
            'status',
            'update_time',
            'create_time'
        ];

    }
}