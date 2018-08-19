<?php
namespace app\index\controller;
use app\common\lib\yzx\Ucpaas;
use app\common\lib\Util;
use app\common\lib\redis\Predis;
use app\common\lib\Redis;

class Login
{
	public function index()
	{
		// 验证验证码
		$phoneNum=intval($_GET['phone_num']);
		$code=intval($_GET['code']);
		// 判断
 		if(empty($phoneNum) || empty($code)) {
            return Util::show(config('code.error'), 'phone or code is error');
        }

		try {
            $redisCode = Predis::getInstance()->get(Redis::smsKey($phoneNum));
        }catch (\Exception $e) {
            echo $e->getMessage();
        }
        // 判断传递过来的code是否与redis里面的key
        if($redisCode == $code) {
            // 写入redis
            $data = [
                'user' => $phoneNum,
                'srcKey' => md5(Redis::userkey($phoneNum)),
                'time' => time(),
                'isLogin' => true,
            ];
            Predis::getInstance()->set(Redis::userkey($phoneNum), $data);

            return Util::show(config('code.success'), 'ok', $data);
        } else {
            return Util::show(config('code.error'), 'login error');
        }        		

	}
}
