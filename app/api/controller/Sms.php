<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/22
 * Time: 14:23
 */
namespace app\api\controller;

use think\Controller;

class Sms extends Controller{

    public function sendSms(){
        $mobile = input('post.mobile');
        $sendUrl = 'http://v.juhe.cn/sms/send'; //短信接口的URL

        $code = $srand = rand(1000,9999);

        $smsConf = array(
            'key'   => 'f2f996a99ffbb73e52e235e0d9beabe1', //您申请的APPKEY
            'mobile'    => $mobile, //接受短信的用户手机号码
            'tpl_id'    => '116106', //您申请的短信模板ID，根据实际情况修改
            'tpl_value' =>'#code#='.$code //您设置的模板变量，根据实际情况修改
        );

        $content = juhecurl($sendUrl,$smsConf,1); //请求发送短信

        if($content){
            $result = json_decode($content,true);
            $error_code = $result['error_code'];
            if($error_code == 0){
                return json(['code'=>200,'msg'=>'发送成功','data'=>$code]);
                //状态为0，说明短信发送成功
//                echo "短信发送成功,短信ID：".$result['result']['sid'];
            }else{
                //状态非0，说明失败
//                $msg = $result['reason'];
//                echo "短信发送失败(".$error_code.")：".$msg;
                return json(['code'=>400,'msg'=>'发送失败']);
            }
        }else{
            //返回内容异常，以下可根据业务逻辑自行修改
//            echo "请求发送短信失败";
            return json(['code'=>400,'msg'=>'发送失败']);
        }
    }

}