<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/21 0021
 * Time: 上午 10:42
 */
namespace app\api\Controller;

use think\Controller;
use think\Db;
use think\Request;

header("Access-Control-Allow-Origin: *");
class Base extends Controller{

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
        if (empty(Request::instance()->header('token'))){
            echo  json(array('code' => 403, 'msg' => '必须传入token'))->send();
            exit();
        }else{
            $user_token = Db::name('user_token')->where('token_value',Request::instance()->header('token'))->find();
            if ($user_token){
                $date = date('Y-m-d H:i:s');
                if ($date < $user_token['token_expire_time']){
                    $user = Db::name('user')->where('user_id',$user_token['token_user_id'])->find();
                    $this->user = $user;
                }else{
                    echo  json(array('code' => 402, 'msg' => 'token过期，请重新登录'))->send();
                    Db::name('user_token')->where('token_value',Request::instance()->header('token'))->update(['token_expire_time'=>date('Y-m-d H:i:s',strtotime("+1 hour"))]);
                    exit();
                }
            }
        }
    }

    /**
     * 判断用户是否登录
     */
//    public function isLogin(){
//        if (request()->isGet()){
//            $param = input('get.');
////            array_splice($param,0,1);
//        }else if (request()->isPost()){
//            $param = input('post.');
//        }
//        if (empty($param)){
//            echo  json(array('code' => 400, 'msg' => '接口参数错误'))->send();
//            exit();
//        }else{
//            if (isset($param['token'])){
//                $utoken = $param['token'];
//                unset($param['token']);
//                $token = api_sign($param);
//                if ($token != $utoken){
//                    echo  json(array('code' => 400, 'msg' => 'token错误'))->send();
//                    exit();
//                }else{
//                    if (isset($param['user_id'])){
//                        $user = Db::name('user')->where('user_id',$param['user_id'])->find();
//                        if (empty($user)){
//                            echo  json(array('code' => 400, 'msg' => '用户不存在，请注册'))->send();
//                            exit();
//                        }else{
//                            if ($user['user_status'] == 0){
//                                echo  json(array('code' => 400, 'msg' => '用户被禁封了'))->send();
//                                exit();
//                            }else{
//                                $this->user = $user;
//                            }
//                        }
//                    }
//                }
//            }else{
//                echo  json(array('code' => 400, 'msg' => '请设置token'))->send();
//                exit();
//            }
//        }
//    }

}