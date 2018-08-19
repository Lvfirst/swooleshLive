<?php 



$http=new swoole_http_server('0.0.0.0',8811);
// 配置访问静态文件
$http->set(
	[
		'document_root'=>'/root/swoole/swooleSaishi/thinkPHP/tp51r/public/static',
		'enable_static_handler'=>true,
		'worker_num'=>4,
	]
);
$http->on('WorkerStart',function($server,$workerId){
 // 定义应用目录
    define('APP_PATH', __DIR__ . '/../application/');
    //请求应该在request里面执行
    // require __DIR__ . '/../thinkphp/start.php';
    // 加载框架文件
    require __DIR__ . '/../thinkphp/base.php';
    
});

$http->on('request',function($request,$response) use ($http){
	// print_r($request->server);
	// 获取请求的内容
	$_SERVER  =  [];
	// swool进程是不释放超全局变量的,判断存在从获取一次
	if(isset($request->server)) {
		foreach($request->server as $k => $v) {
			$_SERVER[strtoupper($k)] = $v;
		}
	}
	if(isset($request->header)) {
		foreach($request->header as $k => $v) {
			$_SERVER[strtoupper($k)] = $v;
		}
	}

	$_GET = []; //初始化空 解决变量为空
	if(isset($request->get)) {
		foreach($request->get as $k => $v) {
			$_GET[$k] = $v;
		}
	}
	$_POST = [];
	if(isset($request->post)) {
		foreach($request->post as $k => $v) {
			$_POST[$k] = $v;
		}
	}
	// 执行响应,这里是输出在了控制台
	// 可以从缓冲区获取内容
	ob_start();
	  try {
        think\Container::get('app', [APP_PATH])
            ->run()
            ->send();
    }catch (\Exception $e) {
        // todo
        var_dump($e);
    }
    #因为是路径的问题 所以用路径这开始追朔
    // echo '-action-'.request()->action().PHP_EOL;
    $con=ob_get_contents();
    ob_end_clean();
 
    // 取出缓冲区的内容
    $response->header('content-type','text/html;charset=utf-8');
    $response->end($con);
    // 释放掉资源，
    // $http->close();
    # 这块终端出现报错，但是还可以正常显示tp，是因为swoole一个worker倒下了 会拉起第二个进程来
});

$http->start();