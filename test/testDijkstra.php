<?php

use common\Matrix;
use strategy\Dijkstra;

require_once "../vendor/autoload.php";

$asset_8x8 = json_decode(file_get_contents('../assets/8x8.json'), true);
$extra = $asset_8x8['simple1'];

$map = new Matrix(8, 8, $extra);
$dijkstra = new Dijkstra($map->getPoint(), $map->getPoint(7, 5), $map);
$dijkstra->start();
