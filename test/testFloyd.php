<?php

use common\Matrix;
use strategy\AStar;
use strategy\Floyd;

;

require_once "../vendor/autoload.php";

$asset_8x8 = json_decode(file_get_contents('../assets/8x8.json'), true);
$extra = $asset_8x8['simple1'];

$map = new Matrix(8, 8, $extra);
$src_point = $map->getPoint();
$dst_point = $map->getPoint(7, 5);
$a_star = new AStar($src_point, $dst_point, $map);
// 取消标准输出
ob_start();
$a_star->start();
$content = ob_get_contents();
ob_end_clean();

// 获取邻接矩阵
$adjacent_matrix = $map->getAdjacentMatrix($map->getRoad($dst_point));
Floyd::getNearestDistance($adjacent_matrix, $src_point, $dst_point);
