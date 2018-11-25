<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/16 0016
 * Time: 下午 3:57
 */
namespace app\admin\Controller;
use think\Controller;
use think\Auth;
use think\Db;
class Common extends Controller{
    /**
     * 权限检测
     */
    protected function _initialize(){
        if (empty(session('admin.admin_id'))) {
            $this->redirect('Login/index');
        }
        $controller = request()->controller();
        $action = request()->action();
        $auth = new Auth();
        if (!$auth->check($controller . '/' . $action, session('admin.admin_id'))) {
            $this->error('你没有权限访问');
        }
        $this->assign('str',$this->menus());
        $this->assign('avatar',config('default_avatar'));
    }

    /**
     * @return array
     * 左菜单
     */
     public function menus(){
         $id = session('admin.admin_id');
         $res = Db::name('auth_group_access')->where('uid',$id)->find();
         if ($res){
             $result = Db::name('auth_group')->where('id',$res['group_id'])->find();
             if (empty($result['rules'])){
                 $this->error('此组别无权限');
             }else{
                 $arr = explode(',',$result['rules']);
                 foreach ($arr as $k){
                     $str[] = Db::name('auth_rule')->where('id',$k)->find();
                 }
                 return generateTree($str);
             }
         }else{
             $this->error('错误');
         }
    }

    //空操作
    public function _empty(){
        abort(404,'页面不存在');
//        exception();
    }

}
