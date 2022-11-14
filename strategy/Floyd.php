<?php

namespace strategy;

class Floyd
{
    private static function initPath(array $adjacent_matrix): array
    {
        $path = [];
        foreach ($adjacent_matrix as $i => $row) {
            $path[$i] = [];
            foreach ($row as $j => $distance) {
                $path[$i][$j] = $j;
            }
        }
        return $path;
    }

    public static function getNearestDistance(array $adjacent_matrix): array
    {
        $path = self::initPath($adjacent_matrix);
        for ($i = 0; $i < count($adjacent_matrix); $i++) {
            // i插入的点， (j -> i -> k) < (j -> k)
            for ($j = 0; $j < count($adjacent_matrix); $j++) {
                // j为起始点
                if ($i == $j) {
                    continue;
                }
                for ($k = 0; $k < count($adjacent_matrix); $k++) {
                    // k为终止点
                    if ($k == $i || $k == $j) {
                        continue;
                    }
                    // j => i => k < j => k
                    $tmp = ($adjacent_matrix[$j][$i] == 'inf' || $adjacent_matrix[$i][$k] == 'inf') ?
                        'inf' : $adjacent_matrix[$j][$i] + $adjacent_matrix[$i][$k];
                    if ($adjacent_matrix[$j][$k] == 'inf') {
                        $adjacent_matrix[$j][$k] = $tmp;
                    } elseif ($tmp != 'inf') {
                        if ($tmp < $adjacent_matrix[$j][$k]) {
                            $adjacent_matrix[$j][$k] = $tmp;
                            $path[$j][$k] = $path[$i][$k];
                        }
                    }
                }
            }
        }
        return [$adjacent_matrix, $path];
    }

    private static function getMaxNameLength(array $names)
    {
        $max_name_length = 0;
        foreach ($names as $name) {
            $max_name_length = strlen($name) > $max_name_length ? strlen($name) : $max_name_length;
        }
        return $max_name_length;
    }

    private static function getMaxDistanceLength(array $adjacent_matrix)
    {
        $max_length = 0;
        foreach ($adjacent_matrix as $row) {
            foreach ($row as $distance) {
                $max_length = $max_length > strlen($distance) ? $max_length : strlen($distance);
            }
        }
        return $max_length;
    }

    public static function printAdjacentMatrix(array $adjacent_matrix, array $names)
    {
        $count = self::getMaxNameLength($names);
        $max_distance_length = self::getMaxDistanceLength($adjacent_matrix);
        $output_length = 2 + (1 + $count + 3 + $count + 3 + $max_distance_length + 3) * count($adjacent_matrix) - 2 + 1;
        echo str_repeat("*", $output_length) . PHP_EOL;
        foreach ($adjacent_matrix as $i => $row) {
            echo "* ";
            foreach ($row as $j => $distance) {
                echo sprintf("<%{$count}s, %{$count}s> = %-{$max_distance_length}s", $names[$i], $names[$j], $distance);
                if ($j < count($row) - 1) {
                    echo " | ";
                } else {
                    echo " ";
                }
            }
            echo "*" . PHP_EOL;
        }
        echo str_repeat("*", $output_length) . PHP_EOL;
    }

    public static function getPath($start, $end, array $path, array $names)
    {
        // TODO: 路径有问题
        return null;
        echo "start = {$start}, end = {$end}\n";
        if ($path[$start][$end] == $start) {
            echo "{$names[$start]} {$names[$end]}";
        } else {
            self::getPath($start, $path[$start][$end], $path, $names);
            echo "{$names[$end]} ";
        }
    }
}
