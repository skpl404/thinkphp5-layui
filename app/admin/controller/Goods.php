<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/19
 * Time: 13:59
 */
namespace app\admin\controller;

use think\Db;
use think\Exception;

class Goods extends Common {

    public function goodslist(){
        $this->assign('title','商品列表');
        return $this->fetch();
    }
    public function goodsData(){
        $nowPage=input('get.page');
        $num=input('get.limit');
        $data = Db::name('goods')
            ->page($nowPage,$num)
            ->order('goods_id desc')
            ->select();
        $count = Db::name('goods')->count();
        $json['code'] = 0;
        $json['msg'] = '';
        $json['data'] = $data;
        $json['count'] = $count;
        echo json_encode($json);
    }

    public function add_goods(){
        $goods_id = input('goods_id');
        $res = Db::name('goods')->where('goods_id',$goods_id)->find();
        $this->assign('info',$res);
        $terr = Db::name('terrace')->select();
        $this->assign('terr',$terr);
        $type = Db::name('shop_type')->select();
        $this->assign('type',$type);
        $trade = Db::name('trade')->select();
        $this->assign('trade',$trade);
        if (request()->isPost()){
            $data = input('post.');
            $arr['goods_indicate'] = $data['indicate'];
            $arr['goods_title'] = $data['title'];
            $arr['goods_subtitle'] = $data['subtitle'];
            $arr['goods_original_price'] = $data['original_price'];
            $arr['goods_current_price'] = $data['current_price'];
            $arr['goods_link'] = $data['link'];
            $arr['goods_stock'] = $data['stock'];
            $arr['goods_browse'] = $data['browse'];
            $arr['goods_sales'] = $data['sales'];
            $arr['goods_status'] = $data['status'];
            $arr['goods_img'] = $data['img'];
            $arr['goods_terr'] = $data['terr'];
            $arr['goods_type'] = $data['type'];
            $arr['goods_trade'] = $data['trade'];
            if (empty($data['goods_id'])){
                $arr['goods_create_time'] = time();
                $res = Db::name('goods')->insertGetId($arr);
                if ($res){
                    $this->redirect('Goods/goodslist');
                }else{
                    $this->error('新增失败');
                }
            }else{
                $arr['goods_update_time'] = time();
                $res = Db::name('goods')->where('goods_id',$data['goods_id'])->update($arr);
                if ($res){
                    $this->redirect('Goods/goodslist');
                }else{
                    $this->error('修改失败');
                }
            }
        }
        return $this->fetch();
    }

    public function del_goods(){
        $id = input('get.goods_id');
        if ($id){
            $res = Db::name('goods')->where('goods_id',$id)->delete();
            if ($res){
                return json(['code'=>200,'msg'=>'删除成功','data'=>$res]);
            }else{
                return json(['code'=>400,'msg'=>'删除失败']);
            }
        }else{
            return json(['code'=>400,'msg'=>'参数有误']);
        }
    }

    public function terrace(){
        $this->assign('title','平台列表');
        return $this->fetch();
    }
    public function terraceData(){
        $nowPage=input('get.page');
        $num=input('get.limit');
        $data = Db::name('terrace')
            ->page($nowPage,$num)
            ->order('terr_id desc')
            ->select();
        $count = Db::name('terrace')->count();
        $json['code'] = 0;
        $json['msg'] = '';
        $json['data'] = $data;
        $json['count'] = $count;
        echo json_encode($json);
    }

    public function add_terrace(){
        $terr_id = input('terr_id');
        $res = Db::name('terrace')->where('terr_id',$terr_id)->find();
        $this->assign('info',$res);
        if (request()->isPost()){
            $data = input('post.');
            $arr['terr_title'] = $data['title'];
            if (empty($data['terr_id'])){
                $arr['terr_create_time'] = time();
                $res = Db::name('terrace')->insertGetId($arr);
                if ($res){
                    $this->redirect('Goods/terrace');
                }else{
                    $this->error('新增失败');
                }
            }else{
                $arr['terr_update_time'] = time();
                $res = Db::name('terrace')->where('terr_id',$data['terr_id'])->update($arr);
                if ($res){
                    $this->redirect('Goods/terrace');
                }else{
                    $this->error('修改失败');
                }
            }
        }
        return $this->fetch();
    }

    public function del_terrace(){
        $id = input('get.terr_id');
        if ($id){
            $res = Db::name('terrace')->where('terr_id',$id)->delete();
            if ($res){
                return json(['code'=>200,'msg'=>'删除成功','data'=>$res]);
            }else{
                return json(['code'=>400,'msg'=>'删除失败']);
            }
        }else{
            return json(['code'=>400,'msg'=>'参数有误']);
        }
    }

    public function shoptype(){
        $this->assign('title','商城类型');
        return $this->fetch();
    }
    public function typeData(){
        $nowPage=input('get.page');
        $num=input('get.limit');
        $data = Db::name('shop_type')
            ->page($nowPage,$num)
            ->order('type_id desc')
            ->select();
        $count = Db::name('shop_type')->count();
        $json['code'] = 0;
        $json['msg'] = '';
        $json['data'] = $data;
        $json['count'] = $count;
        echo json_encode($json);
    }

    public function add_type(){
        $terr = Db::name('terrace')->select();
        $this->assign('terr',$terr);
        $type_id = input('type_id');
        $res = Db::name('shop_type')->where('type_id',$type_id)->find();
        $this->assign('info',$res);
        if (request()->isPost()){
            $data = input('post.');
            $arr['type_title'] = $data['title'];
            $arr['type_terr_id'] = $data['terr'];
            if (empty($data['type_id'])){
                $arr['type_create_time'] = time();
                $res = Db::name('shop_type')->insertGetId($arr);
                if ($res){
                    $this->redirect('Goods/shoptype');
                }else{
                    $this->error('新增失败');
                }
            }else{
                $arr['type_update_time'] = time();
                $res = Db::name('shop_type')->where('type_id',$data['type_id'])->update($arr);
                if ($res){
                    $this->redirect('Goods/shoptype');
                }else{
                    $this->error('修改失败');
                }
            }
        }
        return $this->fetch();
    }

    public function del_type(){
        $id = input('get.type_id');
        if ($id){
            $res = Db::name('shop_type')->where('type_id',$id)->delete();
            if ($res){
                return json(['code'=>200,'msg'=>'删除成功','data'=>$res]);
            }else{
                return json(['code'=>400,'msg'=>'删除失败']);
            }
        }else{
            return json(['code'=>400,'msg'=>'参数有误']);
        }
    }

    public function trade(){
        $this->assign('title','行业列表');
        return $this->fetch();
    }
    public function tradeData(){
        $nowPage=input('get.page');
        $num=input('get.limit');
        $data = Db::name('trade')
            ->page($nowPage,$num)
            ->order('trade_id desc')
            ->select();
        $count = Db::name('trade')->count();
        $json['code'] = 0;
        $json['msg'] = '';
        $json['data'] = $data;
        $json['count'] = $count;
        echo json_encode($json);
    }

    public function add_trade(){
        $trade_id = input('trade_id');
        $res = Db::name('trade')->where('trade_id',$trade_id)->find();
        $this->assign('info',$res);
        if (request()->isPost()){
            $data = input('post.');
            $arr['trade_title'] = $data['title'];
            if (empty($data['trade_id'])){
                $arr['trade_create_time'] = time();
                $res = Db::name('trade')->insertGetId($arr);
                if ($res){
                    $this->redirect('Goods/trade');
                }else{
                    $this->error('新增失败');
                }
            }else{
                $arr['trade_update_time'] = time();
                $res = Db::name('trade')->where('trade_id',$data['trade_id'])->update($arr);
                if ($res){
                    $this->redirect('Goods/trade');
                }else{
                    $this->error('修改失败');
                }
            }
        }
        return $this->fetch();
    }

    public function del_trade(){
        $id = input('get.trade_id');
        if ($id){
            $res = Db::name('trade')->where('trade_id',$id)->delete();
            if ($res){
                return json(['code'=>200,'msg'=>'删除成功','data'=>$res]);
            }else{
                return json(['code'=>400,'msg'=>'删除失败']);
            }
        }else{
            return json(['code'=>400,'msg'=>'参数有误']);
        }
    }

    public function add_category(){
        $goods_id = input('goods_id');
        $cate = Db::name('goods_price')->where('price_goods_id',$goods_id)->select();
        if ($cate){
            foreach ($cate as $key => $val) {
                $str = '';
                $arr = explode(',',$val['price_cate_id']);
                foreach ($arr as $k) {
                    $res = Db::name('goods_category')->where(['category_goods_id'=>$goods_id,'category_id'=>$k])->find();
                    if (empty($str)){
                        $str .= $res['category_value'];
                    }else{
                        $str .= ',' . $res['category_value'];
                    }
                }
                $cate[$key]['str'] = $str;
            }
            $this->assign('table',$cate);
            $category = Db::name('goods_category')->field('category_name')->distinct(true)->where('category_goods_id',$goods_id)->select();
            $this->assign('cate',$category);
            foreach ($category as $key => $val){
                $where['category_goods_id'] = $goods_id;
                $where['category_name'] = $val['category_name'];
                $category[$key]['value'] = Db::name('goods_category')->where($where)->select();
            }
            $this->assign('gory',$category);
            $this->assign('goods_id',$goods_id);
            return $this->fetch('add_category_one');
        }else{
            $this->assign('goods_id',$goods_id);
            $this->assign('title',"添加商品规格");
            return $this->fetch('add_category');
        }
    }
    public function category(){
        $goods_id = input('get.goods_id');
        $cate = json_decode(input('get.cate'),true);
        $where["category_goods_id"] = $goods_id;
        $where['category_create_time'] = time();
        $where['category_update_time'] = time();
        foreach ($cate as $key => $val){
            $where['category_name'] = $key;
            foreach ($val as $p){
                $where['category_value'] = $p;
                $res = Db::name('goods_category')->insert($where);
            }
        }
        if ($res){
            return json(['code'=>200,'msg'=>'添加成功','data'=>$res]);
        }else{
            return json(['code'=>400,'msg'=>'添加失败']);
        }
    }

    public function category_price(){
        $arr = json_decode(input('post.pop'),true);
        $str = json_decode(input('post.str'),true);
        foreach ($arr as $key => $val) {
            $name = '';
            foreach ($str as $map) {
                $where['category_value'] = $val[$map];
                $where['category_goods_id'] = $val['goods_id'];
                $category = Db::name('goods_category')->where($where)->find();
                if (empty($name)){
                    $name .= $category['category_id'];
                }else{
                    $name .= ',' . $category['category_id'];
                }
                $arr[$key]['cate_id'] = $name;
            }
        }
        foreach ($arr as $k => $v) {
            $as['price_cate_id'] = $v['cate_id'];
            $as['price_goods_id'] = $v['goods_id'];
            $as['price_money'] = $v['price'];
            $as['price_img'] = $v['file_img'];
            $as['price_stock'] = $v['stock'];
            $as['price_create_time'] = time();
            $as['price_update_time'] = time();
            Db::name('goods_price')->insertGetId($as);
        }
        return json(['code'=>200,'msg'=>'成功','data'=>$arr,'str'=>$str]);
    }

    public function overload(){
        $goods_id = input('get.goods_id');
        Db::startTrans();
        try{
            Db::name('goods_price')->where('price_goods_id',$goods_id)->delete();
            Db::name('goods_category')->where('category_goods_id',$goods_id)->delete();
            $return = array('code'=>200,'msg'=>'重载成功');
            Db::commit();
        }catch (Exception $e){
            Db::rollback();
            $return = array('code' => 400,'msg'=>$e->getMessage());
        }
        if ($return['code'] == 200){
            $this->success('重载成功','goods/goodslist');
        }else{
            $this->error('重载失败');
        }
    }

}