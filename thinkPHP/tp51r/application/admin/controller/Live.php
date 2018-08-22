<?php
namespace app\admin\controller;

use app\common\lib\Util;
use app\common\lib\redis\Predis;
class live
{
	/**
	 * [push 接收提交的数据并切压入队列]
	 *
	 * @DateTime 2018-08-22
	 *
	 * @return   [type]
	 */
    public function push()
    {
    	$serv=$_POST['http_server'];
    	if(empty($_GET)) {
    		return Util::show(config('code.error'), 'error');
    	} 	
    	// echo 222;
    	try {
    		$fds=Predis::getInstance()->smembers(config('redis.live_game_key'));

    		foreach ($fds as  $fd) {
    			$serv->push($fd,'this is a message');
    		}
    		
    	} catch (\Exception $e) {
    		// echo 4;
    		var_dump($e);
    	}
    	// 获取全部连接的fd
    	
    	// print_r($_GET);
    	//
    	// $serv->push(2,'lvzhiwei');
    }
}
