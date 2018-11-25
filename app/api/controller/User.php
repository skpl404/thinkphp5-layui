<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/23 0023
 * Time: 上午 11:01
 */
namespace app\api\Controller;

use think\Db;
use think\Request;

class User extends Base {
    //用户详情
    public function userInfo(){
        $obj = new \app\api\model\User();
        $user_id = $this->user['user_id'];
        $userInfo = $obj->userInfo($user_id);
        if ($userInfo){
            return json(['code'=>200,'msg'=>'获取成功','data'=>$userInfo]);
        }else{
            return json(['code'=>400,'msg'=>'获取失败']);
        }
    }

    //修改信息
    public function updateAvatar(){
        $user_id = $this->user['user_id'];
        $map['user_name'] = $this->param['name'];
        $map['user_qqnumber'] = $this->param['number'];
        $res = Db::name('user')->where('user_id',$user_id)->update($map);
        if ($res){
            return json(['code'=>200,'msg'=>'修改成功','data'=>$res]);
        }else{
            return json(['code'=>400,'msg'=>'修改失败']);
        }
    }

    //修改密码
    public function updatePwd(){
        $user_id = $this->user['user_id'];
        $obj = new \app\api\model\User();
        $userInfo = $obj->userInfo($user_id);
        if ($userInfo['user_pwd'] == md5($this->param['pwd'])){
            if ($this->param['new_pwd'] == $this->param['update_pwd']){
                $res = Db::name('user')->where('user_id',$user_id)->update(['user_pwd'=>md5($this->param['new_pwd'])]);
                if ($res){
                    return json(['code'=>200,'msg'=>'修改成功','data'=>$res]);
                }else{
                    return json(['code'=>400,'msg'=>'修改失败']);
                }
            }else{
                return json(['code'=>402,'msg'=>'新密码和确认密码不一致']);
            }
        }else{
            return json(['code'=>401,'msg'=>'原始密码不正确']);
        }
    }

    //忘记密码
    public function forget(){
        $user = Db::name('user')->where('user_mobile',$this->param['mobile'])->find();
        if ($user){
            if ($this->param['new_pwd'] == $this->param['update_pwd']){
                $res = Db::name('user')->where('user_mobile',$this->param['mobile'])->update(['user_pwd'=>md5($this->param['new_pwd'])]);
                if ($res){
                    return json(['code'=>200,'msg'=>'修改成功','data'=>$res]);
                }else{
                    return json(['code'=>400,'msg'=>'修改失败']);
                }
            }else{
                return json(['code'=>402,'msg'=>'新密码和确认密码不一致']);
            }
        }else{
            return json(['code'=>401,'msg'=>'此手机号未注册']);
        }
    }

}