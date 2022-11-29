<?php

$days = 2;
$dp = [];
$costs = [
    'Westminster Abbey' => 0.5,
    'The Globe Theatre' => 0.5,
    'National Gallery, UK' => 1,
    'British Museum' => 2,
    'Saint Paul\'s Cathedral' => 0.5,
];
$scores = [
    'Westminster Abbey' => 7,
    'The Globe Theatre' => 6,
    'National Gallery, UK' => 9,
    'British Museum' => 9,
    'Saint Paul\'s Cathedral' => 8,
];

$places = array_keys($costs);
foreach (range(0, count($costs)) as $i) {
    $dp[$i] = [];
    for ($j = 0; $j <= $days * 10; $j += 5) {
        if ($i == 0 || $j == 0) {
            $dp[$i][$j] = [
                'cost' => 0,
                'places' => [],
                'score' => 0,
            ];
            continue;
        }
        $dp[$i][$j] = json_decode(json_encode($dp[$i - 1][$j]), true);
        if ($costs[$places[$i - 1]] * 10 <= $j) {
            // 装入
            if ($dp[$i][$j]['score'] < $dp[$i - 1][$j - $costs[$places[$i - 1]] * 10]['score'] + $scores[$places[$i - 1]]) {
                $dp[$i][$j]['score'] = $dp[$i - 1][$j - $costs[$places[$i - 1]] * 10]['score'] + $scores[$places[$i - 1]];
                $dp[$i][$j]['places'] = array_merge(
                    $dp[$i - 1][$j - $costs[$places[$i - 1]] * 10]['places'],
                    [$places[$i - 1]]
                );
                $dp[$i][$j]['cost'] = $costs[$places[$i - 1]] * 10 + $dp[$i - 1][$j - $costs[$places[$i - 1]] * 10]['cost'];
            }
        }
    }
}

function print_places($costs, $scores, $places)
{
    echo "========== print places =========" . PHP_EOL;
    foreach ($places as $place) {
        echo sprintf("%22s", $place) . " | " . sprintf("%3s", $costs[$place]) . " | " . sprintf("%2s", $scores[$place]) . " |" . PHP_EOL;
    }
}

function print_process($process, $places)
{
    echo PHP_EOL . "========== print process ==========" . PHP_EOL;
    foreach ($process as $index => $row) {
        if (!$index) {
            echo sprintf("%22s", "place ");
            foreach ($row as $day => $node) {
                echo " | " . sprintf("%20s", $day / 10) . " | ";
            }
            echo PHP_EOL;
            continue;
        }
        echo sprintf("%22s", "{$places[$index - 1]}");
        foreach ($row as $node) {
            echo " | COST: " . sprintf("%3s", $node['cost'] / 10) . ", SCORE: " . sprintf("%2s", $node['score']) . " | ";
        }
        echo PHP_EOL;
    }
}

print_places($costs, $scores, $places);
print_process($dp, $places);
echo PHP_EOL . "========== result ==========" . PHP_EOL;
echo "max score: {$dp[count($places)][$days * 10]['score']}" . PHP_EOL;
echo "places: " . implode(" -> ", $dp[count($places)][$days * 10]['places']) . PHP_EOL;
