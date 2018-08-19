<?php 
namespace app\common\lib\redis;

class Predis
{
	public $redis='';

	private static $instance=null;

	// 单例
	public static function  getInstance()
	{
		if(empty(self::$instance))
		{
			self::$instance=new self();
		}
		return self::$instance;
	}

	private function __construct()
	{
		$this->redis=new \Redis();
		// 第三个参数是设置连接超时的时间
		$result = $this->redis->connect(config('redis.host'), config('redis.port'), config('redis.timeOut'));
		if($result===false)
		{
			throw new \Exception('redis connect failed');
		}
	}

	#自定义方法
	#
	public function set($key,$value,$time=0)
	{
		if(!$key)
		{
			return '';
		}
		if(is_array($value))
		{
			$value=json_encode($value);
		}

		// 判断是否设定周期
		if(!$time)
		{
			return $this->redis->set($key,$value);
		}

		return $this->redis->setex($key,$time,$value);
	}
	public function get($key)
	{
		if(!$key)
		{
			return '';
		}

		return $this->redis->get($key);
	}
	/**
	 * [__call 其余方法则通过魔术方法调用即可]
	 *
	 * @DateTime 2018-08-17
	 *
	 * @param    [type] $name
	 * @param    [type] $args
	 *
	 * @return   [type]
	 */
	public function __call($name,$args)
	{
		if(count($arguments) != 2) {
            return '';
        }

        $this->redis->$name($arguments[0], $arguments[1]);			
	}
}