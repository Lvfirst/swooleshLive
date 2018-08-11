<?php 
class Ws{

	const port='8811';
	const wsaddr="0.0.0.0"; 

	private $serv=null;

	public function __construct()
	{
		$this->serv=new swoole_websocket_server(self::wsaddr, self::port);
		/*$this->serv->set(
			[
				'worker_num'=>'1',
				'document_root'=>'/root/swoole/swooleSaishi/HTTP/data',
				'enable_static_handler'=>true,
			]
		);*/

		$this->serv->on('open',[$this,'onOpen']);
		$this->serv->on('message',[$this,'onMessage']);
		$this->serv->on('close',[$this,'onClose']);

		
	}

	public function onOpen($serv,$request)
	{

		echo "server: handshake success with fd{$request->fd}\n";
	}


	public function onMessage($serv,$frame)
	{
		echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
		$serv->push($frame->fd, $frame->data);		
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