<?php
/**
* Created by PhpStorm.
 * User: Administrator
* Date: 2018/8/24 0024
* Time: 上午 10:50
*/
namespace app\api\controller;

use think\Cache;
use think\Controller;
use think\Db;

class File extends Controller{

    public function upload(){
        $file = request()->file('file');
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                $arr = $info->getInfo();
                $map['file_ext'] = $info->getExtension();
                $map['file_savename'] = $info->getSaveName();
                $map['file_filename'] = $info->getFilename();
                $map['file_url'] = '/uploads/'.date('Ymd').'/'.$info->getFilename();
                $map['file_type'] = $info->getType();
                $map['file_size'] = $info->getSize();
                $map['file_tmpname'] = $arr['tmp_name'];
                $map['file_infotype'] = $arr['type'];
                $map['file_create_time'] = time();
                Db::name('file')->insert($map);
                return json(['code'=>200,'msg'=>'success','data'=>$map['file_url']]);
            }else{
                return json(['code'=>400,'msg'=>'fail','data'=>$file->getError()]);
            }
        }
    }

}