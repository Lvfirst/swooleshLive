<?php 
$client=new Swoole\Client(SWOOLE_SOCK_TCP); //TCP  client
$client->connect('127.0.0.1',9501) || exit("errcode:{$client->errCode}");


// php cli 常量
fwrite(STDOUT,"输入消息:");

$msg=trim(fgets(STDIN));

$client->send($msg);

$recv=$client->recv();

echo $recv;
$client->close();