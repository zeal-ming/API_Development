<?php
/**
 * Created by PhpStorm.
 * User: intern
 * Date: 2017/11/1
 * Time: 下午1:46
 */

namespace app\api\controller;


class Test extends Base {

    public function index(){

//        return show('1','可以访问',[],500);
//        $this->testSign();
//        $this->checkSign();
    }

    public function show(){
        echo 'show,post';
    }

    public function create(){
        echo 'create';
    }

    public function read($id){
        echo 'read'.$id;
    }

    public function edit($id){
        echo 'edit'.$id;
    }

    public function update($id){

        echo 'update'.$id;
    }

    public function delete($id){
        echo 'delete'.$id;
    }

}