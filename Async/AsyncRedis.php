<?php 
$redisClient=new swoole\redis;

$redisClient->connect('127.0.0.1',6379,function($redisClient,$result){
	echo "connect".PHP_EOL;
	var_dump($result);

	/*$redisClient->set('lvzhiwei',time(),function($redis,$result){
		var_dump($result);
	});*/

/*	$redisClient->get('lvzhiwei',function($redis,$result){
		// var_dump($redis);
		var_dump($result);
		$redis->close();
	});*/
	$redisClient->keys('*hiw*',function($redis,$res){
		var_dump($res);
		$redis->close();
	});
	
});



