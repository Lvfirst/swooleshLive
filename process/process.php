<?php

$process=new swoole_process(function($worker){
	$worker->exec('/usr/bin/php',[__DIR__.'/server.php']);
},false);

$pid=$process->start();
echo $pid.PHP_EOL;

// 内存回收机制 
swoole_process::wait();