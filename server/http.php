<?php

require_once "../vendor/autoload.php";

use Swoole\Process;
use function Swoole\Coroutine\run;

$proc1 = new Process(function (Process $process) {
    $socket = $process->exportSocket();
    echo $socket->recv();
    $socket->send("hello master");
    echo "proc1 stop\n";
}, false, 1, true);

$proc1->start();

run(function () use ($proc1) {
    $socket = $proc1->exportSocket();
    $socket->send("hello proc1\n");
    var_dump($socket->recv());
});

Process::wait(true);

exit();

$http = new Swoole\Http\Server('0.0.0.0', '1026');

$http->on('start', function ($server) {
    echo "启动一个http服务: {$server->host}:{$server->port}" . PHP_EOL;
});

$http->on('request', function (Swoole\Http\Request $request, Swoole\Http\Response $response) {
    // log
    $path_info = $request->server['path_info'];
    var_dump($path_info);
    if ($path_info == '/') {
        $response->end('Hello');
        return;
    }
    $path_info = explode('/', $path_info);
    var_dump($path_info);
    $response->header('Content-Type', 'application/json;charset=utf-8');
    $response->end(json_encode(['id' => 1, 'name' => 2]));
});

pcntl_signal(SIGINT, function ($sig, $fd) {
    posix_kill(getmypid(), SIGTERM);
});
pcntl_signal_dispatch();

$http->start();
