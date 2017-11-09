<?php
/**
 * Created by PhpStorm.
 * User: intern
 * Date: 2017/11/7
 * Time: 下午1:54
 */

namespace app\api\controller\v1;

use app\common\lib\Aes;
use app\common\lib\ApiException;
use think\Controller;
use app\common\lib\IAuth;
use think\Cache;
use think\Exception;
use think\Validate;

class Login extends Controller{

    //验证码登录验证或用户登录验证
    public function save(){

        if(!$this->request->isPost()){
            return show(0, '请求不合法',[],400);
        }

        //判断是验证码登录还是密码登录
        $data = input('post.');

        if(empty($data)){
            return show(0,'请求不合法',[],500);
        }

        if(isset($data['code'])){

            //解密
            $data = $this->explainVerify($data['code']);
            if(!$data){
                return show(0,'验证码不正确',[],400);
            }

            $validate = new Validate(['phone'=>'require|number|length:11','code'=>'require|number|length:4']);

            if(!$validate->check($data)){
                return show(0,$validate->getError(),[],400);
            }

            //验证验证码是否正确
            if(Cache::get($data['phone']) != $data['code']){

                return show(0,'验证码不正确',[],400);
            }

            //生成token
            $token = IAuth::setAppAccessToken($data['phone']);

            //判断用户是否是第一次登录,第一次登录就添加一条新用户数据,不是就更新用户数据
            $res = model('User')->get(['phone' => $data['phone']]);

            //用户不存在,
            if(empty($res)){

                //设置存入数据库
                $condition = [
                    'username' => 'zm'.$data['phone'],
                    'password' => '123456',
                    'token' => $token,
                    'phone' => $data['phone'],
                    'time_out' => strtotime('+7 days')
                ];

                $user_id = model('User')->save($condition);

            } else {

                //用户存在,更新token,time_out
                $data = [
                    'token' => $token,
                    'time_out' => strtotime('+7 days'),  //设置有效期
                    'status' => 1,
                    'update_time' => time(),
                ];

                $user_id = model('User')->save($data,['id'=>$res->id]);
            }

        } else {


            $validate = new Validate([
                'username' => 'require',
                'password' => 'require',
            ]);

            if(!$validate->check($data)){
                return show(0, $validate->getError(),[],500);
            }

            $user = model('User')->get(['username' => $data['username']]);

            if(!$user){
                return show(0,'该用户不存在',['loginCode'=>0,'msg'=>'用户不存在'],400);
            }

            if($user->status != 1){
                return show(0,'用户未激活',['loginCode'=>-1,'msg'=>'用户未激活'],400);
            }

            if(empty($user->password)){
                return show(0,'未完成注册',['loginCode'=>2,'msg'=>'未完成注册'],400);
            }

            if($user->password != md5($data['password'])){
                return show(0,'密码不正确',['loginCode'=>3,'msg'=>'密码不正确'],400);
            }

            //生成token,更新表...
            $token = IAuth::setAppAccessToken($data['username']);

            model('User')->save(['token' => $token,'time_out' => strtotime('+7 day')],['id'=>$user['id']]);
        }

        //给前端返回数据,携带token
        //为了安全性,需要对token进行加密
        $result = [
            'token' => (new Aes())->encrypt($token.'||'.'ak47')
        ];

        return show(1,'登录成功', $result,200);
    }


    //处理前端发过来的验证码
    public function explainVerify($code){

        if(empty($code)){
            throw new ApiException('验证码不能为空',500);
        }
        //解密
        $str = (new Aes())->decrypt($code);

        //分解

        try {
            parse_str($str,$data);
        } catch (Exception $e) {
            throw new ApiException('验证码不正确',500);
        }

        return $data;
    }

}