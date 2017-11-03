<?php
/**
 * Created by PhpStorm.
 * AdminUser: intern
 * Date: 2017/10/31
 * Time: 下午2:52
 */

namespace app\admin\controller;


use think\Controller;

class News extends Controller{

    private $obj;

    protected function _initialize()
    {
        $this->obj = model('News');
    }

    public function index(){

        //查询条件
        $data = [];

        //存储返回数据, 以返回前端
        $postData = [
            'catid' => 0,
            'title' => '',
            'start_time' => '',
            'end_time' => ''
        ];

        if(request()->isPost()){
            //根据查询条件获取数据(分类,日期,名称)
            $postData = input('post.');

            //如果有分类
            if($postData['catid']){
                $data['catid'] = $postData['catid'];
            }

            //如果有名称
            if($postData['title']){
                $data['title'] = ['like','%'.$postData['title'].'%'];
            }

            //如果时间框前后位置写错,(调过来)
            $temp = $postData['start_time'];
            $postData['start_time'] = $postData['end_time'];
            $postData['end_time'] = $temp;

//            dump($data);
            //如果有日期
            //只有开始日期
            if($postData['start_time']){
                $data['create_time'] = [
                    'gt', strtotime($postData['start_time'])
                ];

            }
            //只有结束日期
            if($postData['end_time']){
                $data['create_time'] = [
                    '<', strtotime($postData['end_time'])
                ];
            }

            //既有开始,又有结束
            if($postData['start_time'] && $postData['end_time']){
                $data['create_time'] = [
                    ['<', strtotime($postData['end_time'])],
                    ['>', strtotime($postData['start_time'])]
                ];
            }

        }

        //过滤
        //查询数据库
        $news = $this->obj->getNewsByConditions($data);

//        dump($this->obj->getLastSql());

        return $this->fetch('', [
            'news' => $news,
            'data' => $postData
        ]);
    }

    public function add(){

        if(request()->isPost()){
            $data  = input('post.');
            dump($data);

            //数据验证...
            //数据入库
            $res = $this->obj->save($data);

            if(!$res){
                $this->error('保存失败');
            }

            $this->success('保存成功');

        } else {
            return $this->fetch();
        }
    }
}