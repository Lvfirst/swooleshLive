<?php 
class Ws{

	const port='8811';
	const wsaddr="0.0.0.0"; 

	private $serv=null;

	public function __construct()
	{
		$this->serv=new swoole_websocket_server(self::wsaddr, self::port);
		$this->serv->set(
			[
				'worker_num'=>'1',
				'task_worker_num'=>2,//使用task 必须填写这个参数
				// 'document_root'=>'/root/swoole/swooleSaishi/HTTP/data',
				// 'enable_static_handler'=>true,
			]
		);

		$this->serv->on('open',[$this,'onOpen']);
		$this->serv->on('message',[$this,'onMessage']);
		$this->serv->on('task',[$this,'onTask']);
		$this->serv->on('finish',[$this,'onFinish']);
		$this->serv->on('close',[$this,'onClose']);

		
	}

	public function onOpen($serv,$request)
	{
		echo "server: handshake success with fd{$request->fd}\n";
	}

	public function onMessage($serv,$frame)
	{
		echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";  
		$data=[
			'task'=>1,
			'fd'=>$frame->fd,
		];
		// 投递到任务
		$serv->task($data);
		$serv->push($frame->fd, $frame->data);		
	}



	public function onTask($serv,$taskId,$fromId,$data)
	{
		print_r($data);
		//模拟耗时
		sleep(10);
		return 'on task finish'; //是否出发	
	}
	public function onFinish($serv,$taskId,$data)
	{
		echo "Task Id:{$taskId}\n";
		echo "finish received data '{$data}'".PHP_EOL;
	}	
	
	public function onClose($serv,$fd)
	{
		echo "client {$fd} closed\n";
	}

	public function start()
	{
		$this->serv->start();
	}
}

$ws=new Ws();
$ws->start();