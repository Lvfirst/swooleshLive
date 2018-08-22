<?php
namespace app\admin\controller;

use app\common\lib\Util;

class Image
{
    public function index()
    {
    	// var_dump(UPLOAD_PATH);
    	// die;
    	$file=request()->file('file');
    	// var_dump($file);
    	$info=$file->move(UPLOAD_PATH.'static/upload/');
    	if($info) {
            $data = [
                'image' => config('live.host').'/upload/'.$info->getSaveName(),
            ];
            return Util::show(config('code.success'), 'OK', $data);
        }else {
            return Util::show(config('code.error'), 'error');
        }  	
    	// var_dump($info->getSaveName());
    	// var_dump($info);

      	
    	// return json(['name'=>'lvzhiwei','age'=>18,'level'=>'A']);
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }

  
}
