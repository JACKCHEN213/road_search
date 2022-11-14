<?php

use strategy\Floyd;

require_once "../vendor/autoload.php";

// 获取邻接矩阵
$nodes = json_decode(file_get_contents('../assets/nodes_7x7.json'), true);
// $nodes = json_decode(file_get_contents('../assets/nodes_6x6.json'), true);
// $nodes = json_decode(file_get_contents('../assets/nodes_5x5.json'), true);
Floyd::printAdjacentMatrix($nodes['adjacent_matrix'], $nodes['names']);
list($nodes['adjacent_matrix'], $path) = Floyd::getNearestDistance($nodes['adjacent_matrix']);
Floyd::printAdjacentMatrix($nodes['adjacent_matrix'], $nodes['names']);
// Floyd::getPath(0, count($nodes['adjacent_matrix']) - 1, $path, $nodes['names']);
