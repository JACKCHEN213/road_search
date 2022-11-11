<?php

use common\Matrix;
use strategy\AStar;
use strategy\BreadthFirstSearch;
use strategy\Bresenham;
use strategy\Dijkstra;
use strategy\GreedyBestFirstSearch;

require_once "vendor/autoload.php";

$asset_8x8 = json_decode(file_get_contents('assets/8x8.json'), true);
$extra = $asset_8x8['simple1'];

$map = new Matrix(8, 8, $extra);
$breadth_first_search = new BreadthFirstSearch($map->getPoint(), $map->getPoint(7, 5), $map);
$breadth_first_search->start();
$dijkstra = new Dijkstra($map->getPoint(), $map->getPoint(7, 5), $map);
$dijkstra->start();
$greedy_best_first_search = new GreedyBestFirstSearch($map->getPoint(), $map->getPoint(7, 5), $map);
$greedy_best_first_search->start();
$a_star = new AStar($map->getPoint(), $map->getPoint(7, 5), $map);
$a_star->start();
$bresenham = new Bresenham($map->getPoint(), $map->getPoint(7, 5), $map);
$bresenham->start();
