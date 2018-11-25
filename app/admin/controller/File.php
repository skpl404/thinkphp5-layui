<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/25 0025
 * Time: 上午 10:26
 */
namespace app\admin\controller;

use think\Controller;
use think\Db;

class File extends Controller{

    public function upload(){
        $file = request()->file('file');
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $url = '/uploads/'.date('Ymd').'/'.$info->getFilename();
                return json(['code'=>200,'msg'=>'success','data'=>$url]);
            }else{
                return json(['code'=>400,'msg'=>'fail','data'=>$file->getError()]);
            }
        }
    }

}