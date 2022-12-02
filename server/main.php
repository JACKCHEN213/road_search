<?php

require_once "../vendor/autoload.php";

$server = new Swoole\WebSocket\Server('0.0.0.0', '1025');

$server->on('start', function ($server) {
    echo "启动WS服务: {$server->host}:{$server->port}" . PHP_EOL;
});

$server->on('open', function ($server, $req) {
    echo "新的连接: {$req->fd}\n";
});

$server->on('message', function ($server, $frame) {
    echo "接收到消息: {$frame->data}\n";
    $server->push($frame->fd, json_encode(['hello', 'world']));
});

$server->on('close', function ($server, $fd) {
    echo "断开连接: {$fd}\n";
});

$server->start();
