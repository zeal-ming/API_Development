<?php
/**
 * Created by PhpStorm.
 * User: intern
 * Date: 2017/11/7
 * Time: 下午3:09
 */

namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\lib\Aes;
use app\common\lib\ApiException;

use think\Exception;
use think\Log;
use think\Validate;

class User extends Common {

    //验证用户是否登录

    //返回用户信息,为了安全得加密
    public function save(){

        $result = [
            'user' => (new Aes())->encrypt($this->user)
        ];

        return show(1, 'ok',$result, 200);
    }

    //设置用户信息
    public function update(){

        if(!request()->isPut()){
            return show(0,'请求不合法',[],400);
        }

        $data = input('put.');

        //$data是加密后的,这里需要解密...

        //验证
        $validate = new Validate(['username'=>'require|max:32','password'=>'require|max:32','phone'=>'require|number|length:11']);

        if(!$validate->check($data)){
            return show(0,$validate->getError(),[],400);
        }

        //用户名,电话不能有相同...
        $res = model('User')->where('username',$data['username'])->whereOr('phone',$data['phone'])->find();

        if($res){
            return show(0,'用户名或者电话号码已存在',[],400);
        }

        //密码加密后保存...
        $data['password'] = md5($data['password']);

        try {

            $res = model('User')->save($data, ['id' => $this->user->id]);

        } catch (Exception $e){

            Log::write($e->getMessage());
            return show(0,'保存失败',[],500);

        }

        return show(1,'保存成功',[],200);
    }

    //退出
    public function logout(){

        //清空token,time_out
        $data = [
            'token' => '',
            'time_out' => 0
        ];

        try {
            model('User')->save($data,['id'=>$this->user->id]);
        } catch (Exception $e){

            throw new ApiException($e->getMessage());
        }

        return show(1,'成功退出',[],200);

    }

}