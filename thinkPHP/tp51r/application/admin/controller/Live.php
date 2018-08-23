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
    /*	try {
    		$fds=Predis::getInstance()->smembers(config('redis.live_game_key'));
    		if(empty($fds))
    		{
    			return '';
    		}

    		foreach ($fds as  $fd) {
    			$serv->push($fd,'this is a message');
    		}
    		
    	} catch (\Exception $e) {
    		// echo 4;
    		var_dump($e);
    	}*/

  		$teams = [
            1 => [
                'name' => '马刺',
                'logo' => '/live/imgs/team1.png',
            ],
            4 => [
                'name' => '火箭',
                'logo' => '/live/imgs/team2.png',
            ],
        ];
        $data = [
            'type' => intval($_GET['type']),
            'title' => !empty($teams[$_GET['team_id']]['name']) ?$teams[$_GET['team_id']]['name'] : '直播员',
            'logo' => !empty($teams[$_GET['team_id']]['logo']) ?$teams[$_GET['team_id']]['logo'] : '',
            'content' => !empty($_GET['content']) ? $_GET['content'] : '',
            'image' => !empty($_GET['image']) ? $_GET['image'] : '',
        ];
        //print_r($_GET);
        // 获取连接的用户
        // 赛况的基本信息入库   2、数据组织好 push到直播页面
        $taskData = [
            'method' => 'pushLive',
            'data' => $data,
        ];

        $serv->task($taskData);
        return Util::show(config('code.success'), 'ok');    	
    	// 获取全部连接的fd
    	
    	// print_r($_GET);
    	//
    	// $serv->push(2,'lvzhiwei');
    }
}
