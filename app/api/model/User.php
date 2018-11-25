<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/23 0023
 * Time: 上午 11:02
 */
namespace app\api\model;

use think\Model;

class User extends Model{

    protected $autoWriteTimestamp = true;

    // 定义时间戳字段名
    protected $createTime = 'user_reg_time';
    protected $updateTime = 'user_update_time';

    // 关闭自动写入update_time字段
//    protected $updateTime = false;

    //数据完成
    protected $auto = [];
    protected $insert = ['user_reg_ip','user_status' => 1,'user_avatar'];
    protected $update = ['user_update_time'];

    protected function setUserRegIpAttr()
    {
        return request()->ip();
    }

    protected function setUserUpdateTimeAttr()
    {
        return time();
    }

    public function setUserPwdAttr($value)
    {
        return md5($value);
    }

    protected function setUserAvatarAttr()
    {
        return config('default_avatar');
    }

    protected function getUserSexAttr($val)
    {
        switch($val){
            case '1' :
                return '男';
            case '2':
                return '女';
            default:
                return '未知';
        }
    }

    protected function getUserStatusAttr($val)
    {
        $status = [0=>'禁用',1=>'正常'];
        return $status[$val];
    }

    //注册
    public function register($data){
        $bool = $this->save($data);
        return $bool ? $this : '';
    }

    //用户详情
    public function userInfo($userId){
        $data = User::get($userId);
        return $data->toArray();
    }


}