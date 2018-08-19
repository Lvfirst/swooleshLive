<?php
namespace app\index\controller;
use app\common\lib\yzx\Ucpaas;
use app\common\lib\Util;
use app\common\lib\Redis;

class Send
{
	/**
	 * [index send code]
	 *
	 * @DateTime 2018-08-17
	 *
	 * @return   [type]
	 */
    public function index()
    {
    	// bug : 接收如果不刷新  第一次是什么之后的不变化
    	// $phoneNum = request()->get('phone_num', 0, 'intval');
    	$phoneNum=intval($_GET['phone_num']);
    	if(empty($phoneNum)) {
            // status 0 1  message data
            return Util::show(config('code.error'), 'error');
        }

        // 随机数  组合数据 投递到任务
        $code = rand(1000, 9999);

        $taskData = [
        	'method' => 'sendSms',
        	'data' => [
        		'phone' => $phoneNum,
        		'code' => $code,
        	]
        ]; 

        $_POST['http_server']->task($taskData);

        return Util::show(config('code.success'), 'success');
	/*	try {

			$options=[
	    		'accountsid'=>'3c6bfb7871ed3008ba700e4654d30811',
	    		'token'=>'43fc4570ab7c0c13b555e62d914f181c',
	    	];
			//初始化 $options必填
	    	$ucpass = new Ucpaas($options);
	    	$templateid = "180973";    
	    	$param = $code; 
	    	$mobile =$phoneNum;
	    	$uid='';
	    	$r=$ucpass->SendSms($templateid,$param,$mobile,$uid);    		
	    	$response=json_decode($r,true);
	    	// var_dump($response);
	    	
    		
    	} catch (\Exception $e) {
    		return Util::show(config('code.error'), 'error');
    	}  
*/
    	// redis
    	/*if($response['code']=='000000')
    	{
    		// echo 2;
    		// go(function() use($phoneNum,$param)
    		// 	{
		    		$redis=new \Swoole\Coroutine\Redis;
		    		$redis->connect(config("redis.host"),config('redis.port'));
		    		$redis->set(Redis::smsKey($phoneNum),$param,config('redis.out_time'));
    		// 	}
	    	// );
		    return Util::show(config('code.success'), 'success');

    	} 
		else {
			return Util::show(config('code.error'), 'error');
		}  */   
  

    }

}
