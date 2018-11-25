<?php
namespace app\api\Controller;

use think\Db;
use think\Request;

class Usertoken extends Common {
    //注册
    public function register(){
        $obj = new \app\api\model\User();
        if ($this->param['confirm_pwd'] == $this->param['password']){
            $arr['user_mobile'] = $this->param['mobile'];
            $arr['user_pwd'] = $this->param['password'];
            $arr['user_pay_pwd'] = $this->param['paypwd'];
            $arr['user_name'] = $this->param['username'];
            $arr['user_qqnumber'] = $this->param['qqnumber'];
            $arr['user_code'] = invitationCode();
            $res = $obj->register($arr);
            if ($res){
                return json(['code'=>200,'msg'=>'注册成功','data'=>$res]);
            }else{
                return json(['code'=>400,'msg'=>'注册失败']);
            }
        }else{
            return json(['code'=>400,'msg'=>'密码和确认密码不一样']);
        }
    }

    //登录
    public function login(){
        $data = [
            'user_name' =>  $this->param['name'],
            'user_pwd'  =>  md5($this->param['password']),
            'user_status'   =>  1
        ];
        $user = Db::name('user')->where($data)->find();
        if ($user){
            $user_token =  Db::name('user_token')->where('token_user_id',$user['user_id'])->find();
            if (empty($user_token)){
                $token = md5($this->param['name'] . $this->param['password']);
                $data = [
                    'token_user_id' =>  $user['user_id'],
                    'token_value'   =>  $token,
                    'token_create_time' =>  date('Y-m-d H:i:s'),
                    'token_expire_time' =>  date('Y-m-d H:i:s',strtotime("+1 hour")) ,
                ];
                $result = Db::name('user_token')->insert($data);
                if (!$result){
                    return json(['code'=>401,'msg'=>'生成token失败']);
                }else{
                    $user['token'] = $token;
                    return json(['code'=>200,'msg'=>'获取成功','data'=>$user]);
                }
            } else{
                $user['token'] = $user_token['token_value'];
                return json(['code'=>200,'msg'=>'获取成功','data'=>$user]);
            }
        }else{
            return json(['code'=>400,'msg'=>'信息有误']);
        }
    }

}