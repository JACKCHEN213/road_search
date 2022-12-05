<?php

require_once "../vendor/autoload.php";

use common\map\Matrix;
use strategy\ex\BreadthFirstSearchEx;

$http = new Swoole\Http\Server('0.0.0.0', '1026');

$http->on('start', function ($server) {
    echo "启动一个http服务: {$server->host}:{$server->port}" . PHP_EOL;
});

$asset_8x8 = json_decode(file_get_contents('../assets/8x8.json'), true);
$extra = $asset_8x8['simple1'];
$map = new Matrix(8, 8, $extra);

$http->on('request', function (Swoole\Http\Request $request, Swoole\Http\Response $response) use ($map) {
    // log
    try {
        $path_info = $request->server['path_info'];
        echo "新的请求：[{$request->getMethod()}] " . $path_info . PHP_EOL;
        $response->header("Access-Control-Allow-Origin", "*");
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            $response->header("Access-Control-Allow-Origin", $_SERVER['HTTP_ORIGIN']);
        }
        $response->header("Access-Control-Allow-Methods", "GET, POST, DELETE");
        $response->header("Access-Control-Allow-Headers", "Authorization,DNT,X-Mx-ReqToken,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type, Accept-Language, Origin, Accept-Encoding");
        $response->header('Content-Type', 'application/json;charset=utf-8');
        if (strtolower($request->getMethod()) == 'options') {
            $response->end();
            return null;
        }

        $content_type = $request->header['content-type'] ?? '';
        if (
            $path_info != '/road_search'
            || strtolower($request->getMethod()) == 'get'
            || strpos($content_type, 'json') === false
        ) {
            throw new Exception('', 1);
        }
        $request_data = json_decode($request->getContent(), true);
        if (is_null($request_data)) {
            throw new Exception('错误的json数据', 2);
        }
        /**
         * status
         */
        if (!isset($request_data['status'])) {
            throw new Exception('数据缺少索引: status', 2);
        }
        if ($request_data['status'] == 'init') {
            $ret_data = [
                'map' => $map->getMap(true),
                'src_point' => $map->getPoint(0, 0)->toJson(),
                'dst_point' => $map->getPoint(7, 5)->toJson(),
                'open_list' => '[]',
                'close_list' => '[]',
            ];
            $response->end(json_encode($ret_data));
        } else {
            $response->end(json_encode(['id' => 1, 'name' => 2]));
        }
    } catch (Exception $e) {
        if ($e->getCode() != 1) {
            echo "请求错误: " . $e->getMessage() . PHP_EOL;
            $response->end(json_encode(['msg' => $e->getMessage()]));
        } else {
            $response->end('Hello');
        }
    }
});

$http->start();
