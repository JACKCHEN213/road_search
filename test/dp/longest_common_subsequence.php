<?php

$src = "international";
$dst = "name";

$dp = [];

for ($i = 0; $i <= strlen($src); $i++) {
    $dp[$i] = [];
    for ($j = 0; $j <= strlen($dst); $j++) {
        $dp[$i][$j] = [
            'count' => 0,
            'chars' => [],
        ];
        if ($i == 0 || $j == 0) {
            continue;
        }
        if ($src[$i - 1] == $dst[$j - 1]) {
            $dp[$i][$j]['count'] = $dp[$i - 1][$j - 1]['count'] + 1;
            $dp[$i][$j]['chars'] = array_merge($dp[$i - 1][$j - 1]['chars'], [$dst[$j - 1]]);
        }
    }
}

function print_process($dp, $src, $dst)
{
    echo PHP_EOL . "========= print process ==========" . PHP_EOL;
    $min_length = strlen($src) < strlen($dst) ? strlen($src) : strlen($dst);
    $min_bit = strlen(strval($min_length));
    echo "  ";
    for ($i = 0; $i <= strlen($dst); $i++) {
        if ($i) {
            echo "| " . sprintf("%" . ($min_bit + $min_length + 17) . "s", $dst[$i - 1]) . " | ";
        } else {
            echo "| " . sprintf("%" . ($min_bit + $min_length + 17) . "s", "-") . " | ";
        }
    }
    echo PHP_EOL;
    foreach ($dp as $i => $row) {
        if ($i) {
            echo $src[$i - 1] . " ";
        } else {
            echo "- ";
        }
        foreach ($row as $node) {
            echo "| count: " . sprintf("%{$min_bit}s", $node['count']) . ", char: \""
                . sprintf("%{$min_length}s", implode("", $node['chars'])) . "\" | ";
        }
        echo PHP_EOL;
    }
}

function print_result($dp)
{
    $longest_subsequence = "";
    $max_count = 0;
    $position_src = 0;
    $position_dst = 0;
    foreach ($dp as $i => $row) {
        foreach ($row as $j => $node) {
            if ($max_count < $node['count']) {
                $max_count = $node['count'];
                $longest_subsequence = implode("", $node['chars']);
                $position_src = $i - $max_count;
                $position_dst = $j - $max_count;
            }
        }
    }
    echo PHP_EOL . "========= print result ==========" . PHP_EOL;
    echo "count: {$max_count}, subsequence: {$longest_subsequence},"
        . " src position: {$position_src}, dst position； {$position_dst}" . PHP_EOL;
}

echo "src: {$src}, dst: {$dst}" . PHP_EOL;
print_process($dp, $src, $dst);
print_result($dp);
