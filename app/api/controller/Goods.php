<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/21
 * Time: 14:27
 */
namespace app\api\controller;

use think\Controller;
use think\Db;

class Goods extends Common {
    /**
     * @return \think\response\Json
     * 商品信息
     */
    public function goodsList(){
        $terr = input('get.terr','1');
        $map['g.goods_terr'] = $terr;
        if (input('?get.type')) $map['g.goods_type'] = $this->param['type'];
        if (input('?get.trade')) $map['g.goods_trade'] = $this->param['trade'];
        if (input('?get.title')) {
            $title = $this->param['title'];
            $map['g.goods_title'] = ['like',"%$title%"];
        }
        switch ($this->param['sort']){
            case 1:
                $where['g.goods_current_price'] = 'desc';
                break;
            case 2:
                $where['g.goods_sales'] = 'desc';
                break;
            default:
                $where['g.goods_id'] = 'desc';
        }
        $goods = Db::name('goods')
            ->alias('g')
            ->join('jc_shop_type s','g.goods_type=s.type_id')
            ->join('jc_trade t','g.goods_trade=t.trade_id')
            ->where($map)
            ->order($where)
            ->page($this->param['page'],$this->param['limit'])
            ->select();
        $total = Db::name('goods')
            ->alias('g')
            ->join('jc_shop_type s','g.goods_type=s.type_id')
            ->join('jc_trade t','g.goods_trade=t.trade_id')
            ->where($map)
            ->order($where)
            ->page($this->param['page'],$this->param['limit'])
            ->count();
        if ($goods){
            return json(['code'=>200,'msg'=>'获取成功','data'=>$goods,'count'=>$total]);
        }else{
            return json(['code'=>400,'msg'=>'获取失败']);
        }
    }

    /**
     * @return \think\response\Json
     * 获取商品详情
     */
    public function goodsDetail(){
        $result = Db::name('goods')
            ->alias('g')
            ->join('jc_shop_type t','g.goods_type=t.type_id')
            ->join('jc_trade d','g.goods_trade=d.trade_id')
            ->where('g.goods_id',$this->param['goods_id'])
            ->find();
        $cate = Db::name('goods_category')
            ->field("category_name")
            ->where('category_goods_id', $this->param['goods_id'])
            ->group('category_name')
            ->select();
        if (!$cate) {
            return json(['code' => 400, 'msg' => '此商品没有规格']);
        }
        foreach ($cate as $key => $val) {
            $cate[$key]['cate'] = Db::name('goods_category')
                ->field('category_id,category_value')
                ->where(['category_name' => $val['category_name'], 'category_goods_id' => $this->param['goods_id']])
                ->select();
        }
        if ($result){
            return json(['code'=>200,'msg'=>'获取成功','data'=>['goods'=>$result],'category'=>$cate]);
        }else{
            return json(['code'=>400,'msg'=>'获取失败']);
        }
    }

}