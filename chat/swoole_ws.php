<?php

use Swoole\WebSocket\Server;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;

$server = new Server("0.0.0.0", 9502);

$connections = [];

$server->on("Start", function(Server $server)
{
    echo "Swoole WebSocket Server is started at http://127.0.0.1:9502\n";
});

$server->on('Open', function(Server $server, Swoole\Http\Request $request)
{

    echo "connection open: {$request->fd}\n";



        if(isset($request->fd )){

            $server->tick(1000, function() use ($server, $request)
            {
                echo "connection open: {$request}\n";
            $server->push($request->fd, json_encode(["hello", $request->fd]));
            });
        }


});

$server->on('Message', function(Server $server, Frame $frame)
{
    echo "received Make: {$frame->data}\n";
    echo "received Id: {$frame->fd}\n";
    $server->push($frame->fd, json_encode(["hello", time()]));
});

$server->disconnect('Disconnect', function(Server $server, int $fd)
{
    echo "connection disconnect: {$fd}\n";
});

$server->on('Close', function(Server $server, int $fd)
{

    echo "connection close: {$fd}\n";
});

$server->on('Disconnect', function(Server $server, int $fd)
{
    echo "connection disconnect: {$fd}\n";
});

$server->start();
