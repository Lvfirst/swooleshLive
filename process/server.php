<?php 
$http=new swoole_http_server('0.0.0.0',8881);
// 配置访问静态文件
$http->set(
	[
		'worker_num'=>'8',
		'document_root'=>'/root/swoole/swooleSaishi/HTTP/data',
		'enable_static_handler'=>true,
	]
);

$http->on('request',function($request,$response){
	$uid=$request->get;
	$str=json_encode($uid);
	 $content = [
        'date:' => date("Ymd H:i:s"),
        'get:' => $request->get,
        'post:' => $request->post,
        'header:' => $request->header,
    ];
    
    swoole_async_writefile(__DIR__."/access.log", json_encode($content).PHP_EOL, function($filename){
        // todo
    }, FILE_APPEND);
	$response->cookie('lvzhiwei','18',time()+1200);
	$response->end("<h1>Hello{$str} Swoole #".rand(1000,9999)."</h1>");
});

$http->start();