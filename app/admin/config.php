<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/16 0016
 * Time: 下午 2:42
 */
//配置文件
return [
    //模板路径配置
    'template'               => [
        // 模板路径
        'view_path'    => config('template.view_path').config('admin.model_name').'/'.config('admin.default_template').'/',
    ],

    'default_avatar' => 'http://'.$_SERVER['HTTP_HOST'].'/static/index/images/default_avatar.png',

    //模板路径
    'view_replace_str'       => [
        '__ADMIN__' => '/static/admin/js',
        '__LAYUI__' => '/static/layui',
        '__STATIC__' => '/static/admin',
    ],

    //权限配置
    'auth'  => [
        'auth_on'           => 1, // 权限开关
        'auth_type'         => 1, // 认证方式，1为实时认证；2为登录认证。
        'auth_group'        => 'auth_group', // 用户组数据不带前缀表名
        'auth_group_access' => 'auth_group_access', // 用户-用户组关系不带前缀表
        'auth_rule'         => 'auth_rule', // 权限规则不带前缀表
        'auth_user'         => 'admin', // 用户信息不带前缀表
    ],

    // 开启应用Trace调试
    'app_trace' =>  true,

    //验证码配置
    'captcha'=>[
        //验证码长度(位数)
        'length'    =>  3,
        // 验证码字体大小
        'fontSize'    =>    30,
        // 关闭验证码杂点
        'useNoise'    =>    false,
    ],

//    'http_exception_template'    =>  [
//        // 定义404错误的重定向页面地址
//        404 =>  APP_TEMPLATE.'/admin/default/404.html',
//        // 还可以定义其它的HTTP status
//        401 =>  APP_PATH.'401.html',//401指未授权
//    ],

];