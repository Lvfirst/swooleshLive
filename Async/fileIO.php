<?php 
// 函数风格
// $filename 返回文件名字
// $content 文件内容
swoole_async_readfile(__DIR__.'/1.txt',function($filename,$content){
	echo "{$filename}:{$content}\n";
});

// 命名空间
$res=Swoole\Async::readFile(__DIR__.'/1.txt',function($filename,$content){
	echo "{$filename}:{$content}\n";
});

var_dump($res);