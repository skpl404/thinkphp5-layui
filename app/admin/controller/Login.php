<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/17 0017
 * Time: 下午 3:25
 */
namespace app\admin\Controller;

use think\Config;
use think\Controller;
use think\Db;

class Login extends Controller{

    public function index(){
        return $this->fetch();
    }

    /**
     * 登录
     */
    public function login(){
        $name = input('post.name');
        $pwd = input('post.password');
        $code = input('post.code');
        $res = Db::name('admin')->where('admin_username',$name)->find();
        if (!empty($res)){
            if ($res['admin_pwd'] == $pwd){
                if ($res['admin_status'] == 1){
                    if (captcha_check($code)){
                        session('admin',$res);
                        Db::name('admin')->where('admin_id',$res['admin_id'])->setField('admin_last_time',time());
                        $this->redirect('Auth/index');
                    }else{
                        $this->error('验证码不正确','Login/index');
                    }
                }else{
                    $this->error('用户被禁用');
                }
            }else{
                $this->error('密码错误');
            }
        }else{
            $this->error('用户不错在的');
        }
    }

    /**
     * @return \think\Response
     * 验证码
     */
    public function show_captcha(){
        $captcha = new \think\captcha\Captcha(Config::get('captcha'));
        return $captcha->entry();
    }

    /**
     * 退出
     */
    public function layout()
    {
        // 删除（当前作用域）
        session('admin', null);
        // 清除think作用域
        session(null, 'think');
        // 清除session（当前作用域）
        session(null);
        $this->success('退出成功！','Login/index');
    }


}