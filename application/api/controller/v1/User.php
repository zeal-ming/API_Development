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
use think\Cache;
use think\Exception;
use think\Log;
use think\Validate;

class User extends Common {

    //验证用户是否登录

    //返回用户信息,为了安全得加密
    public function index(){

        $result = [
            'user' => (new Aes())->encrypt($this->user)
        ];

        return show(1, 'ok',$result, 200);
    }

    //设置用户信息
    public function save(){

        if(!request()->isPost()){
            return show(0,'请求不合法',[],400);
        }
        $data = input('post.');

        //验证
        $validate = new Validate(['username'=>'require|max:32','password'=>'require|max:32','phone'=>'require|number|length:11']);

        if(!$validate->check($data)){
            return show(0,$validate->getError(),[],400);
        }

        try {

            $res = model('User')->save($data, ['id' => $this->user->id]);

        } catch (Exception $e){
            Log::write($e->getMessage());

        }

        if(!$res){
            return show(1,'保存失败',[],500);
        }

        return show(1,'保存成功',[],200);
    }

    //用户登录验证
    public function check(){

        if(!request()->isPost()) return show(0,'请求不正确',[],500);

        $data = input('post.');

        $validate = new Validate([
            'username' => 'require',
            'password' => 'require',
        ]);

        if(!$validate->check($data)){
            return show(0, $validate->getError(),[],500);
        }

        $user = model('User')->get(['username'=>$data['username']]);

        if(!$user){
            return show(0,'该用户不存在',[],500);
        }

        if($user->password != $data['password']){
            return show(0,'密码不正确',[],500);
        }

        return show(0,'登录成功',[],200);


    }


}