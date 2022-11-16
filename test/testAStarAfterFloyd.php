<?php

use common\map\Matrix;
use strategy\AStar;
use strategy\Floyd;

require_once "../vendor/autoload.php";

$asset_8x8 = json_decode(file_get_contents('../assets/8x8.json'), true);
$extra = $asset_8x8['simple1'];

$map = new Matrix(8, 8, $extra);
$a_star = new AStar($map->getPoint(), $map->getPoint(7, 5), $map);
ob_start();
$a_star->start();
$content = ob_get_contents();
ob_end_clean();
list($adjacent_matrix, $names) = $map->getAdjacentMatrix($map->getRoad($map->getPoint(7, 5)));
Floyd::printAdjacentMatrix($adjacent_matrix, $names);
list($adjacent_matrix, $path) = Floyd::getNearestDistance($adjacent_matrix, $names);
Floyd::printAdjacentMatrix($adjacent_matrix, $names);
$road = Floyd::getPath($adjacent_matrix, 0, count($adjacent_matrix) - 1, $path);
echo "<{$names[0]},{$names[count($adjacent_matrix) - 1]}>: " . implode(" -> ", array_map(function ($node) use ($names) {
        return $names[$node];
}, $road)) . PHP_EOL;
echo $content;
