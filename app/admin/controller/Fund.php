<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/20
 * Time: 19:08
 */
namespace app\admin\controller;

use think\Db;

class Fund extends Common{

    public function gain(){
        $this->assign('title','收益明细');
        return $this->fetch();
    }
    public function gainData(){
        $nowPage = input('get.page');
        $num = input('get.limit');
        $id = input('get.id');
        if ($id){
            $where["f.fund_order_sn|u.user_mobile"] = array('like',"%$id%");
            $data = Db::name('fund')
                ->alias('f')
                ->join('jc_user u','f.fund_user_id=u.user_id')
                ->page($nowPage,$num)
                ->where($where)
                ->order('f.fund_id desc')
                ->select();
            $count = Db::name('fund')
                ->alias('f')
                ->join('jc_user u','f.fund_user_id=u.user_id')
                ->where($where)
                ->count();
            $json['code'] = 0;
            $json['msg'] = 'success';
            $json['data'] = $data;
            $json['count'] = $count;
            echo json_encode($json);
            exit();
        }
        $data = Db::name('fund')
            ->alias('f')
            ->join('jc_user u','f.fund_user_id=u.user_id')
            ->page($nowPage,$num)
            ->order('f.fund_id desc')
            ->select();
        $count = Db::name('fund')
            ->alias('f')
            ->join('jc_user u','f.fund_user_id=u.user_id')
            ->count();
        $json['code'] = 0;
        $json['msg'] = 'success';
        $json['data'] = $data;
        $json['count'] = $count;
        echo json_encode($json);
    }

    public function verify_check(){
        $status = input('get.status');
        $fund_id = input('get.fund_id');
        $fund = Db::name('fund')->where('fund_id',$fund_id)->find();
        if ($status == 1){
            if ($fund['fund_status'] == 1){
                return json(['code'=>400,'msg'=>'已经审核通过']);
            }elseif ($fund['fund_status'] == 2){
                return json(['code'=>400,'msg'=>'已审核']);
            }
            $res = Db::name('fund')->where('fund_id',$fund_id)->update(['fund_status'=>$status,'fund_update_time'=>time()]);
            if ($res){
                return json(['code'=>200,'msg'=>'审核成功','data'=>$res]);
            }else{
                return json(['code'=>400,'msg'=>'审核失败']);
            }
        }elseif ($status == 2){
            if ($fund['fund_status'] == 1){
                return json(['code'=>400,'msg'=>'已经审核通过']);
            }elseif ($fund['fund_status'] == 2){
                return json(['code'=>400,'msg'=>'已审核']);
            }
            $res = Db::name('fund')->where('fund_id',$fund_id)->update(['fund_status'=>$status,'fund_update_time'=>time()]);
            if ($res){
                return json(['code'=>200,'msg'=>'审核成功','data'=>$res]);
            }else{
                return json(['code'=>400,'msg'=>'审核失败']);
            }
        }else{
            return json(['code'=>400,'msg'=>'审核失败']);
        }
    }

    public function dels_fund(){
        $data = input('get.json');
        $json = json_decode($data,true);
        foreach ($json  as $key => $val) {
            $res = Db::name('fund')->where('fund_id',$val['fund_id'])->delete();
        }
        if ($res){
            return json(['code'=>200,'msg'=>'成功','data'=>$res]);
        }else{
            return json(['code'=>400,'msg'=>'失败']);
        }
    }

    public function pose(){
        $this->assign('title','充值列表');
        return $this->fetch();
    }
    public function poseData(){
        $nowPage = input('get.page');
        $num = input('get.limit');
        $id = input('get.id');
        if ($id){
            $where['u.user_name|u.user_mobile|g.goods_title'] = array('like',"%$id%");
            $data = Db::name('spon')
                ->alias('s')
                ->join('jc_user u','s.spon_user_id=u.user_id')
                ->join('jc_goods g','s.spon_goods_id=g.goods_id')
                ->page($nowPage,$num)
                ->where($where)
                ->order('s.spon_id desc')
                ->select();
            $count = Db::name('spon')
                ->alias('s')
                ->join('jc_user u','s.spon_user_id=u.user_id')
                ->join('jc_goods g','s.spon_goods_id=g.goods_id')
                ->where($where)
                ->count();
            $json['code'] = 0;
            $json['msg'] = 'success';
            $json['data'] = $data;
            $json['count'] = $count;
            echo json_encode($json);
            exit();
        }
        $data = Db::name('spon')
            ->alias('s')
            ->join('jc_user u','s.spon_user_id=u.user_id')
            ->join('jc_goods g','s.spon_goods_id=g.goods_id')
            ->page($nowPage,$num)
            ->order('s.spon_id desc')
            ->select();
        $count = Db::name('spon')
            ->alias('s')
            ->join('jc_user u','s.spon_user_id=u.user_id')
            ->join('jc_goods g','s.spon_goods_id=g.goods_id')
            ->count();
        $json['code'] = 0;
        $json['msg'] = 'success';
        $json['data'] = $data;
        $json['count'] = $count;
        echo json_encode($json);
    }

    public function del_spon(){
        $spon_id = input('get.spon_id');
        if ($spon_id){
            $res = Db::name('spon')->where('spon_id',$spon_id)->delete();
            if ($res){
                return json(['code'=>200,'msg'=>'成功','data'=>$res]);
            }else{
                return json(['code'=>400,'msg'=>'失败']);
            }
        }else{
            return json(['code'=>400,'msg'=>'参数错误']);
        }
    }

    public function dels_spon(){
        $data = input('get.json');
        $json = json_decode($data,true);
        foreach ($json  as $key => $val) {
            $res = Db::name('spon')->where('spon_id',$val['spon_id'])->delete();
        }
        if ($res){
            return json(['code'=>200,'msg'=>'成功','data'=>$res]);
        }else{
            return json(['code'=>400,'msg'=>'失败']);
        }
    }

}