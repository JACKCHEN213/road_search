<?php

$n = 100;
$m = 5;
$milks = [
    [5, 20],
    [9, 40],
    [3, 10],
    [8, 80],
    [6, 30],
];
$money = 0;

array_multisort($milks, array_column($milks, 0));

foreach ($milks as $milk) {
    if ($milk[1] <= $n) {
        $money += $milk[0] * $milk[1];
        $n -= $milk[1];
    } else {
        $money += $milk[0] * $n;
        break;
    }
}

echo $money . PHP_EOL;
