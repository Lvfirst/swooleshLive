<?php 
/**
 * 对于不同的task处理
 * 
 */
namespace app\common\lib\task;
use app\common\lib\Util;
use app\common\lib\yzx\Ucpaas;
use app\common\lib\Redis; //处理key的类型
use app\common\lib\redis\Predis; //写入redis

class Task
{
	/**
	 * [sendSms 发送短信验证码]
	 *
	 * @DateTime 2018-08-19
	 *
	 * @param    [type] $data
	 *
	 * @return   [type]
	 */
	public function sendSms($data)
	{
		try {
            $options=[
                'accountsid'=>'3c6bfb7871ed3008ba700e4654d30811',
                'token'=>'43fc4570ab7c0c13b555e62d914f181c',
            ];
            //初始化 $options必填
            $ucpass = new Ucpaas($options);
            $templateid = "180973";
            $uid='';
            $r=$ucpass->SendSms($templateid,$data['code'],$data['phone'],$uid);

            $response=json_decode($r,true);
            
        } catch (\Exception $e) {
            return false;
        }  

		if($response['code']=='000000')
    	{
    		// 把验证码写入redis
    		 Predis::getInstance()->set(Redis::smsKey($data['phone']), $data['code'], config('redis.out_time'));
    		 return true;
    	} 
		else {
			return false;
		}          
	}
}