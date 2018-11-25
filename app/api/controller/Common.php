<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/21 0021
 * Time: 上午 10:42
 */
namespace app\api\controller;

use think\Controller;
use think\Db;
use think\Request;

header("Access-Control-Allow-Origin: *");
class Common extends Controller{

    //保存登录用户信息
    protected $user = array();
    protected $param;
    /**
     * 控制器初始化方法
     */
    protected function _initialize()
    {
        $this->isLogin();
    }

    protected function isLogin(){
        if (request()->isGet()){
            $this->param = input('get.');
        }else if (request()->isPost()){
            $this->param = input('post.');
        }
    }
}