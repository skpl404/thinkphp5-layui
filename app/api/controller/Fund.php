<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/21
 * Time: 19:00
 */
namespace app\api\controller;

use think\Db;

class Fund extends Base{

    /**
     * @return \think\response\Json
     * 提现
     */
    public function fund(){
        if ($this->user['user_pay_pwd'] == $this->param['pwd']){
            if (luhm($this->param['banknum']) == false){
                return json(['code'=>401,'msg'=>'银行卡号有错误']);
            }
            $arr['fund_user_id'] = $this->user['user_id'];
            $arr['fund_pay_num'] = $this->param['paynum'];
            $arr['fund_user_name'] = $this->param['username'];
            $arr['fund_order_sn'] = getOrderSn();
            $arr['fund_money'] = $this->param['money'];
            $arr['fund_bank_num'] = $this->param['banknum'];
            $arr['fund_create_time'] = time();
            $arr['fund_update_time'] = time();
            $arr['fund_status'] = 0;
            $res = Db::name('fund')->insertGetId($arr);
            if ($res){
                return json(['code'=>200,'msg'=>'申请成功','data'=>$res]);
            }else{
                return json(['code'=>400,'msg'=>'申请失败']);
            }
        }else{
            return json(['code'=>400,'msg'=>'提现密码不正确，请重试']);
        }
    }

    /**
     * @return \think\response\Json
     * 赞助明细
     */
    public function recharge(){
        $page = input('post.page','1');
        $limit = input('post.limit','10');
        $count = Db::name('spon')->count();
        if (!empty($this->param['start']) && !empty($this->param['end'])){
            $data = Db::name('spon')
                ->alias('s')
                ->join('jc_user u','s.spon_user_id=u.user_id')
                ->join('jc_goods g','s.spon_goods_id=g.goods_id')
                ->order('s.spon_id desc')
                ->page($page,$limit)
                ->whereTime('s.spon_create_time','between',[$this->param['start'],$this->param['end']])
                ->select();
            if ($data){
                return json(['code'=>200,'msg'=>'成功','data'=>$data,'total'=>$count]);
            }else{
                return json(['code'=>400,'msg'=>'失败']);
            }
        }
        $data = Db::name('spon')
            ->alias('s')
            ->join('jc_user u','s.spon_user_id=u.user_id')
            ->join('jc_goods g','s.spon_goods_id=g.goods_id')
            ->order('s.spon_id desc')
            ->page($page,$limit)
            ->select();
        if ($data){
            return json(['code'=>200,'msg'=>'成功','data'=>$data,'total'=>$count]);
        }else{
            return json(['code'=>400,'msg'=>'失败']);
        }
    }

    /**
     * @return \think\response\Json
     * 提现列表
     */
    public function withdraw(){
        $page = input('post.page','1');
        $limit = input('post.limit','10');
        $count = Db::name('fund')->count();
        if (!empty($this->param['start']) && !empty($this->param['end'])){
            $res = Db::name('fund')
                ->alias('f')
                ->join('jc_user u','f.fund_user_id=u.user_id')
                ->whereTime('f.fund_create_time','between',[$this->param['start'],$this->param['end']])
                ->page($page,$limit)
                ->select();
            if ($res){
                return json(['code'=>200,'msg'=>'成功','data'=>$res,'total'=>$count]);
            }else{
                return json(['code'=>400,'msg'=>'失败']);
            }
        }
        $res = Db::name('fund')
            ->alias('f')
            ->join('jc_user u','f.fund_user_id=u.user_id')
            ->page($page,$limit)
            ->select();
        if ($res){
            return json(['code'=>200,'msg'=>'成功','data'=>$res,'total'=>$count]);
        }else{
            return json(['code'=>400,'msg'=>'失败']);
        }
    }

}