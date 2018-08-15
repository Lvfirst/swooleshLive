<?php 
echo "process-start-time:".date("Ymd H:i:s");
$worker=[];
$urls = [
    'http://baidu.com',
    'http://sina.com.cn',
    'http://qq.com',
    'http://baidu.com?search=singwa',
    'http://baidu.com?search=singwa2',
    'http://baidu.com?search=imooc',
];

$count=count($urls);

// 开启进程并且把进程存储起来
for($i=0;$i<6;$i++)
{
	$process=new swoole_process(function($worker) use($i,$urls){
		$content=curlData($urls[$i]);
		// 往管道里面写数据
		$worker->write($content);
		// echo $content.PHP_EOL;
	},true);

	$pid=$process->start();
	$worker[$pid]=$process;
}

// 取出管道里面的内容
foreach ($worker as $process) {
	echo PHP_EOL;
	echo $process->read();

}

// process->read 获取管道内容
function curlData($url) {
    // curl file_get_contents
    sleep(1);
    return $url . "success".PHP_EOL;
}
echo "process-end-time:".date("Ymd H:i:s");

# 把任务分配给子进程
# 子进程把内容输出到管道里
# 再通过read() 读取管道里面的内容