<?php 
// 
// 协程客户端的限制于这几个回调里使用
// {
// 		onRequest
// 		onConnect
// 		onReceive
// }
// 
// 协程的执行时间
// 比如 同时存在mysql和 redis
// 取出的最后时间大概是  time=max(redis|mysql); 最大值

$serv=new swoole_http_server('0.0.0.0',9001);

$serv->on('request',function($request,$response){
	$key=$request->get['key'];
	echo $key;
	$redis=new Swoole\Coroutine\Redis();
	$redis->connect('127.0.0.1',6379);
	$redis->setex('key-2',10,'value-2');
	$r=$redis->get($key);
	$response->end($r);
});

$serv->start();