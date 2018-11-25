<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/21 0021
 * Time: 上午 9:34
 */
namespace app\admin\Controller;

use think\Db;

class User extends Common{
    //用户列表
    public function user_lists(){
        $this->assign('title','用户列表');
        return $this->fetch();
    }
    public function userLists(){
        $nowPage=input('get.page');
        $num=input('get.limit');
        $data = Db::name('user')
            ->page($nowPage,$num)
            ->select();
        $count = Db::name('user')->count();
        $json['code'] = 0;
        $json['msg'] = '';
        $json['data'] = $data;
        $json['count'] = $count;
        echo json_encode($json);
    }
    //用户等级
    public function user_level(){
        $this->assign('title','用户等级');
        return $this->fetch();
    }
    public function userLevel(){
        $nowPage=input('get.page');
        $num=input('get.limit');
        $data = Db::name('user_level')
            ->page($nowPage,$num)
            ->select();
        $count = Db::name('user_level')->count();
        $json['code'] = 0;
        $json['msg'] = '';
        $json['data'] = $data;
        $json['count'] = $count;
        echo json_encode($json);
    }

    //新增编辑等级
    public function add_level(){
        $level_id = input('level_id');
        $res = Db::name('user_level')->where('level_id',$level_id)->find();
        $this->assign('info',$res);
        if (request()->isPost()){
            $data = input('post.');
            if (empty($data['level_id'])){
                $arr['level_name'] = $data['name'];
                $arr['level_rule'] = $data['rule'];
                $arr['level_status'] = $data['status'];
                $arr['level_create_time'] = time();
                $arr['level_update_time'] = time();
                $res = Db::name('user_level')->insert($arr);
                if ($res){
                    $this->redirect('User/user_level');
                }else{
                    $this->error('新增失败');
                }
            }else{
                $arr['level_name'] = $data['name'];
                $arr['level_rule'] = $data['rule'];
                $arr['level_status'] = $data['status'];
                $arr['level_update_time'] = time();
                $res = Db::name('user_level')->where('level_id',$data['level_id'])->update($arr);
                if ($res){
                    $this->redirect('User/user_level');
                }else{
                    $this->error('编辑失败');
                }
            }
        }
        return $this->fetch();
    }

    //删除等级
    public function del_level(){
        $level_id = input('get.level_id');
        $res = Db::name('user_level')->where('level_id',$level_id)->delete();
        if ($res){
            return json(['code'=>200,'msg'=>'success','data'=>$res]);
        }else{
            return json(['code'=>400,'msg'=>'fail']);
        }
    }

}