<?php

$m = 500;
$n = 6;
$goods = [
    [100, 3],
    [35, 5],
    [50, 10],
    [60, 2],
    [20, 15],
    [5, 6],
];

$columns = array_column($goods, 0);
array_multisort($goods, $columns);
$count = 0;
foreach ($goods as $good) {
    if ($good[0] * $good[1] <= $m) {
        $m -= ($good[0] * $good[1]);
        $count += $good[1];
    } else {
        $count += floor($m / $good[0]);
        $m -= floor($m / $good[0]) * $good[0];
        break;
    }
}

echo "rest: {$m}, count: {$count}\n";
