<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
// 应用公共文件
//树
function tree(&$data,$pid = 0,$count = 1) {
    static $treeList = array();
    foreach ($data as $key => $value){
        if($value['pid']==$pid){
            $value['count'] = $count;
            $treeList []=$value;
//            unset($data[$key]);
            tree($data,$value['id'],$count+1);
        }
    }
    return $treeList;
}
//树
function generateTree($list, $pk = 'id', $pid = 'pid', $child = 'child', $root = 0)
{
    $tree     = array();
    $packData = array();
    foreach ($list as $data) {
        $packData[$data[$pk]] = $data;
    }
    foreach ($packData as $key => $val) {
        if ($val[$pid] == $root) {
            //代表跟节点, 重点一
            $tree[] = &$packData[$key];
        } else {
            //找到其父类,重点二
            $packData[$val[$pid]][$child][] = &$packData[$key];
        }
    }
    return $tree;
}
//签名
function api_sign($param, $origin=false)
{
    $sign = concat($param);
    if($origin){
        return $sign;
    }
    return hash('md5', $sign);
}
function concat(array $param)
{
    ksort($param);
    $first = '';
    foreach ($param as $key => $val) {
        if(is_array($val)) {
            $first .= concat($val);
            continue;
        }
        $first .= $val;
//        if(!empty($val)) {
//            $first .= $val;
//        }
    }
    return $first;
}
//随机8位数字
function invitationCode(){
    $rand = mt_rand(1000,9999);
    $str = date('is');
    return $str.$rand;
}
//短信
function sendMsg($mobile,$tplCode,$tplParam){
    require_once '../extend/aliyun/api_sdk/vendor/autoload.php';
    Config::load();             //加载区域结点配置
    $accessKeyId = config('aliYunSms.app_key');
    $accessKeySecret = config('aliYunSms.app_secret');
    $templateParam = $tplParam; //模板变量替换
    //$signName = (empty(config('alisms_signname'))?'阿里大于测试专用':config('alisms_signname'));
    $signName = config('aliYunSms.sign_name');
    //短信模板ID
    $templateCode = $tplCode;
    //短信API产品名（短信产品名固定，无需修改）
    $product = "Dysmsapi";
    //短信API产品域名（接口地址固定，无需修改）
    $domain = "dysmsapi.aliyuncs.com";
    //暂时不支持多Region（目前仅支持cn-hangzhou请勿修改）
    $region = "cn-hangzhou";
    // 初始化用户Profile实例
    $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
    // 增加服务结点
    DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
    // 初始化AcsClient用于发起请求
    $acsClient= new DefaultAcsClient($profile);
    // 初始化SendSmsRequest实例用于设置发送短信的参数
    $request = new SendSmsRequest();
    // 必填，设置雉短信接收号码
    $request->setPhoneNumbers($mobile);
    // 必填，设置签名名称
    $request->setSignName($signName);
    // 必填，设置模板CODE
    $request->setTemplateCode($templateCode);
    // 可选，设置模板参数
    if($templateParam) {
        $request->setTemplateParam(json_encode($templateParam));
    }
    //发起访问请求
    $acsResponse = $acsClient->getAcsResponse($request);
    //返回请求结果
    $result = json_decode(json_encode($acsResponse),true);
    return $result;
}
//二位数组根据一个健值大小排序排序
function arr_sort($arr){
    $flag = array();
    foreach($arr as $arr2){
        $flag[] = $arr2["money"];
    }
    array_multisort($flag, SORT_DESC, $arr);
    return $arr;
}
//订单号
function getOrderSn(){
    $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
    $orderSn = $yCode[intval(date('Y')) - 2011] . strtoupper(dechex(date('m'))) . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));
    return $orderSn;
}
//聚合短信
function juhecurl($url,$params=false,$ispost=0){
    $httpInfo = array();
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
    curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
    curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
    if( $ispost )
    {
        curl_setopt( $ch , CURLOPT_POST , true );
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
        curl_setopt( $ch , CURLOPT_URL , $url );
    }
    else
    {
        if($params){
            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
        }else{
            curl_setopt( $ch , CURLOPT_URL , $url);
        }
    }
    $response = curl_exec( $ch );
    if ($response === FALSE) {
        //echo "cURL Error: " . curl_error($ch);
        return false;
    }
    $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
    $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
    curl_close( $ch );
    return $response;
}
//银行卡号校验
function luhm($s) {
    $n = 0;
    for ($i = strlen($s); $i >= 1; $i--) {
        $index=$i-1;
        //偶数位
        if ($i % 2==0) {
            $n += $s{$index};
        } else {//奇数位
            $t = $s{$index} * 2;
            if ($t > 9) {
                $t = (int)($t/10)+ $t%10;
            }
            $n += $t;
        }
    }
    return ($n % 10) == 0;
}

//接口统一json返回处理
if(!function_exists('exit_jsons')) {
    function exit_jsons($reData){
        exit(json_encode($reData));
    }
}