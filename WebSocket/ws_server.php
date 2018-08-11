<?php 
$server = new swoole_websocket_server("0.0.0.0", 8811);

$server->set(
	[
		'document_root'=>'/root/swoole/swooleSaishi/HTTP/data',
		'enable_static_handler'=>true,		
	]
);

$server->on('open', function (swoole_websocket_server $server, $request) {
    echo "server: handshake success with fd{$request->fd}\n";
    $server->push($request->fd, "this is open");
});

$server->on('message', function (swoole_websocket_server $server, $frame) {
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";

    $server->push($frame->fd, $frame->data);
});

$server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});

$server->start();