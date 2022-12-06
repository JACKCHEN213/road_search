<?php

require_once "../vendor/autoload.php";

use common\map\Matrix;
use strategy\ex\BreadthFirstSearchEx;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

$http = new Server('0.0.0.0', '1026');

$http->on('start', function ($server) {
    echo "启动一个http服务: {$server->host}:{$server->port}" . PHP_EOL;
});

$asset_8x8 = json_decode(file_get_contents('../assets/8x8.json'), true);
$extra = $asset_8x8['simple1'];
$map = new Matrix(8, 8, $extra);
$strategy = null;
$src_point = $map->getPoint(0, 0);
$dst_point = $map->getPoint(7, 5);

$http->on('request', function (Request $request, Response $response)
 use (&$map, &$strategy, &$src_point, &$dst_point) {
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

        $ret_data = [
            'map' => $map->getMap(true),
            'src_point' => $src_point->toJson(),
            'dst_point' => $dst_point->toJson(),
            'open_list' => '[]',
            'close_list' => '[]',
            'roads' => '[]',
        ];
        if ($request_data['status'] == 'init') {
            $response->end(json_encode($ret_data));
        } elseif ($request_data['status'] == 'start') {
            $strategy = new BreadthFirstSearchEx($src_point, $dst_point, $map);
            $ret_data['open_list'] = $strategy->getOpenList(true);
            $ret_data['close_list'] = $strategy->getCloseList(true);
            if ($strategy->search_status == 'find') {
                $ret_data['roads'] = $map->getRoad($dst_point, true);
            } elseif ($strategy->search_status == 'not_find') {
                $ret_data['roads'] = '["not_find"]';
            }
            $response->end(json_encode($ret_data));
        } elseif ($request_data['status'] == 'next') {
            if ($strategy->search_status == 'find') {
                $ret_data['roads'] = $map->getRoad($dst_point, true);
            } elseif ($strategy->search_status == 'not_find') {
                $ret_data['roads'] = '["not_find"]';
            } else {
                $search_status = $strategy->next();
                if ($search_status == 'find') {
                    $ret_data['roads'] = $map->getRoad($dst_point, true);
                } elseif ($search_status == 'not_find') {
                    $ret_data['roads'] = '["not_find"]';
                }
            }
            $ret_data['open_list'] = $strategy->getOpenList(true);
            $ret_data['close_list'] = $strategy->getCloseList(true);
            $response->end(json_encode($ret_data));
        } elseif ($request_data['status'] == 'restart') {
            $map->initMap();
            $map->initExtra();
            $src_point = $map->getPoint(0, 0);
            $dst_point = $map->getPoint(7, 5);
            $strategy = null;
            $strategy = new BreadthFirstSearchEx($src_point, $dst_point, $map);
            $ret_data['open_list'] = $strategy->getOpenList(true);
            $ret_data['close_list'] = $strategy->getCloseList(true);
            if ($strategy->search_status == 'find') {
                $ret_data['roads'] = $map->getRoad($dst_point, true);
            } elseif ($strategy->search_status == 'not_find') {
                $ret_data['roads'] = '["not_find"]';
            }
            $ret_data['map'] = $map->getMap(true);
            $ret_data['src_point'] = $src_point->toJson();
            $ret_data['dst_point'] = $dst_point->toJson();
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

// TODO: 进程不会退出
$http->start();
