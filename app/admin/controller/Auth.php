<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/16 0016
 * Time: 下午 2:48
 */
namespace app\admin\Controller;

use think\Controller;
use think\Db;
use think\Request;

class Auth extends Common {

    public function index(){
        return view();
    }

    /**
     * @return \think\response\View
     * 用户组别
     */
    public function auth_group(){
        $this->assign('title','组别管理');
        return view();
    }
    public function group_lists(){
        $nowPage=input('get.page');
        $num=input('get.limit');
        $group = Db::name('auth_group')
            ->page($nowPage,$num)
            ->select();
        $count = Db::name('auth_group')->count();
        $json['code'] = 0;
        $json['msg'] = '';
        $json['data'] = $group;
        $json['count'] = $count;
        echo json_encode($json);
    }

    /**
     * @return \think\response\View
     * 用户权限
     */
    public function auth_rule(){
        $this->assign('title','规则管理');
        return view();
    }
    public function rule_lists(){
        $nowPage=input('get.page');
        $num=input('get.limit');
        $group = Db::name('auth_rule')
            ->page($nowPage,$num)
            ->select();
        $count = Db::name('auth_rule')->count();
        $json['code'] = 0;
        $json['msg'] = '';
        $json['data'] = $group;
        $json['count'] = $count;
        echo json_encode($json);
    }

    /**
     * @return \think\response\View
     * 管理员
     */
    public function admin_list(){
        $this->assign('title','管理员');
        return view();
    }
    public function admin_datas(){
        $nowPage=input('get.page');
        $num=input('get.limit');
        $group = Db::name('admin')
            ->page($nowPage,$num)
            ->select();
        $count = Db::name('admin')->count();
        $json['code'] = 0;
        $json['msg'] = '';
        $json['data'] = $group;
        $json['count'] = $count;
        echo json_encode($json);
    }

    /**
     * @return \think\response\View
     * 分配权限
     */
    public function rules(){
        $group_id = input('group_id');
        $rule = Db::name('auth_rule')->where('status=1 and pid=0')->select();
        foreach ($rule as $key=>&$val) {
            $val['two'] = Db::name('auth_rule')->where(['status'=>1,'pid'=>$val['id']])->select();
        }
        foreach ($rule as $k=>&$v){
            foreach ($v['two'] as $p=>&$l){
                $l['three'] = Db::name('auth_rule')->where(['status'=>1,'pid'=>$l['id']])->select();
            }
        }
        $res = Db::name('auth_group')->where('id',$group_id)->find();
        $arr = explode(',',$res['rules']);
        $this->assign('arr',$res['rules']);
        $this->assign('group_id',$group_id);
        $this->assign('rule',$rule);
        $this->assign('title','分配权限');
        return view();
    }

    /**
     * @return \think\response\Json
     * 修改权限
     */
    public function rule_like(){
        $data = input('get.');
        if (!empty($data['group_id']) && !empty($data['rules'])){
            $res = Db::name('auth_group')->where('id',$data['group_id'])->setField('rules',$data['rules']);
            if ($res){
                return json(['code'=>200,'msg'=>'success']);
            }else{
                return json(['code'=>400,'msg'=>'fail']);
            }
        }else{
            $this->error('参数有误');
        }
    }

    /**
     * @return \think\response\Json
     * 批量删除管理员
     */
    public function del_batch_admin(){
        $param = input('get.json');
        $arr = json_decode($param,true);
        $arr = array_column($arr,'admin_id');
        foreach ($arr as $k){
            $res = Db::name('admin')->where('admin_id',$k)->delete();
        }
        if ($res){
            return json(['code'=>200,'msg'=>'success','data'=>$res]);
        }else{
            return json(['code'=>400,'msg'=>'fail']);
        }
    }

    /**
     * @return \think\response\Json
     * 删除管理员
     */
    public function del_admin(){
        $admin_id = input('get.admin_id');
        $res = Db::name('admin')->where('admin_id',$admin_id)->delete();
        if ($res){
            return json(['code'=>200,'msg'=>'success','data'=>$res]);
        }else{
            return json(['code'=>400,'msg'=>'fail']);
        }
    }

    /**
     * @return mixed
     * 新增/编辑管理员
     */
    public function add_admin(){
        $admin_id = input('admin_id');
        $res = Db::name('admin')->where('admin_id',$admin_id)->find();
        $this->assign('info',$res);
        if (request()->isPost()){
            $data = input('post.');
            if (empty($data['admin_id'])){
                $arr['admin_username'] = $data['username'];
                $arr['admin_pwd'] = $data['password'];
                $arr['admin_status'] = $data['status'];
                $arr['admin_create_time'] = time();
                $arr['admin_last_time'] = time();
                $res = Db::name('admin')->insert($arr);
                if ($res){
                    $this->redirect('Auth/admin_list');
                }else{
                    $this->error('新增失败');
                }
            }else{
                $arr['admin_username'] = $data['username'];
                $arr['admin_pwd'] = $data['password'];
                $arr['admin_status'] = $data['status'];
                $res = Db::name('admin')->where('admin_id',$data['admin_id'])->update($arr);
                if ($res){
                    $this->redirect('Auth/admin_list');
                }else{
                    $this->error('编辑失败');
                }
            }
        }
        return $this->fetch();
    }

    /**
     * @return mixed
     * 用户授权
     */
    public function group(){
        $res = Db::name('auth_group')->select();
        $this->assign('group',$res);
        $admin_id = input('param.admin_id');
        $user_group = Db::name('auth_group_access')->where('uid',$admin_id)->find();
        $this->assign('user_group',$user_group['group_id']);
        $this->assign('admin_id',$admin_id);
        if (request()->isPost()){
            $data = input('post.');
            $arr['uid'] = $data['admin_id'];
            $arr['group_id'] = $data['group_id'];
            $res = Db::name('auth_group_access')->insert($arr);
            if ($res){
                $this->redirect('Auth/admin_list');
            }else{
                $this->error('授权失败');
            }
        }
        return $this->fetch();
    }

    /**
     * @return mixed
     * 新增组别
     */
    public function add_group(){
        $rule = Db::name('auth_rule')->where('status=1 and pid=0')->select();
        foreach ($rule as $key=>&$val) {
            $val['two'] = Db::name('auth_rule')->where(['status'=>1,'pid'=>$val['id']])->select();
        }
        foreach ($rule as $k=>&$v){
            foreach ($v['two'] as $p=>&$l){
                $l['three'] = Db::name('auth_rule')->where(['status'=>1,'pid'=>$l['id']])->select();
            }
        }
        $this->assign('rule',$rule);
        if (request()->isPost()){
            $data = input('post.');
            $arr['rules'] = implode(',',$data['like']);
            $arr['title'] = $data['title'];
            $res = Db::name('auth_group')->insert($arr);
            if ($res){
                $this->redirect('Auth/auth_group');
            }else{
                $this->error('新增失败');
            }
        }
        return $this->fetch();
    }

    /**
     * @return \think\response\Json
     * 删除管理组
     */
    public function del_group(){
        $group_id = input('get.group_id');
        $res = Db::name('auth_group')->where('id',$group_id)->delete();
        if ($res){
            return json(['code'=>200,'msg'=>'success','data'=>$res]);
        }else{
            return json(['code'=>400,'msg'=>'fail']);
        }
    }

    /**
     * @return mixed
     * 新增/编辑规则
     */
    public function add_rules(){
        $rule_id = input('get.rule_id');
        $res = Db::name('auth_rule')->where('id',$rule_id)->find();
        $this->assign('info',$res);
        $rule = Db::name('auth_rule')->select();
        $this->assign('rule',generateTree($rule));
        if (request()->isPost()){
            $data = input('post.');
            if ($data['rule_id']){
                $arr['title'] = $data['title'];
                $arr['name'] = $data['rule'];
                $arr['pid'] = $data['pid'];
                $result = Db::name('auth_rule')->where('id',$data['rule_id'])->update($arr);
                if ($result){
                    $this->redirect('Auth/auth_rule');
                }else{
                    $this->error('更新失败');
                }
            }else{
                $arr['title'] = $data['title'];
                $arr['name'] = $data['rule'];
                $arr['pid'] = $data['pid'];
                $result = Db::name('auth_rule')->insert($arr);
                if ($result){
                    $this->redirect('Auth/auth_rule');
                }else{
                    $this->error('新增失败');
                }
            }
        }
        return $this->fetch();
    }

    /**
     * @return \think\response\Json
     * 删除规则
     */
    public function del_rules(){
        $rules_id = input('get.rule_id');
        $res = Db::name('auth_rule')->where('id',$rules_id)->delete();
        if ($res){
            return json(['code'=>200,'msg'=>'success','data'=>$res]);
        }else{
            return json(['code'=>400,'msg'=>'fail']);
        }
    }

}