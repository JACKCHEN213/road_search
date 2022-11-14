<?php

use strategy\Floyd;

require_once "../vendor/autoload.php";

// 获取邻接矩阵
$nodes = json_decode(file_get_contents('../assets/nodes_7x7.json'), true);
$nodes = json_decode(file_get_contents('../assets/nodes_6x6.json'), true);
$nodes = json_decode(file_get_contents('../assets/nodes_5x5.json'), true);
$nodes = json_decode(file_get_contents('../assets/nodes_3x3.json'), true);
$adjacent_matrix = $nodes['adjacent_matrix'];
$names = $nodes['names'];

Floyd::printAdjacentMatrix($adjacent_matrix, $names);
list($adjacent_matrix, $path) = Floyd::getNearestDistance($adjacent_matrix, $names);
Floyd::printAdjacentMatrix($adjacent_matrix, $names);
$road = Floyd::getPath($adjacent_matrix, 0, count($adjacent_matrix) - 1, $path);
echo "<{$names[0]},{$names[count($adjacent_matrix) - 1]}>: " . implode(" -> ", array_map(function ($node) use ($names) {
        return $names[$node];
}, $road)) . PHP_EOL;
