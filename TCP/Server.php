<?php 
//创建Server对象，监听 127.0.0.1:9501端口
$serv = new swoole_server("0.0.0.0", 9501); 

$serv->set(
	[
		"worker_num"=>2, //进程数  1-4倍数
		"max_request"=>10000,
	]
);

//监听连接进入事件
//reactor_id 线程id
$serv->on('connect', function ($serv, $fd,$reactor_id) {  
	echo "Client: {$fd}-{$reactor_id}_Connect.\n";
});

//监听数据接收事件
$serv->on('receive', function ($serv, $fd, $from_id, $data) {
	$serv->send($fd, "Server: ".$data);
});

//监听连接关闭事件
$serv->on('close', function ($serv, $fd) {
	echo "Client: Close.\n";
});

//启动服务器
$serv->start(); 
