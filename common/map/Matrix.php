<?php

namespace common\map;

use strategy\Base;
use common\point\Point;
use common\Color;

class Matrix extends Map
{
    private int $row_len;
    private int $col_len;

    public function __construct($row_len = 4, $col_len = 4, array $extra = [])
    {
        parent::__construct();
        $this->row_len = $row_len;
        $this->col_len = $col_len;
        $this->extra = $extra;
        $this->initMap();
        $this->initExtra();
    }

    public function setExtra(array $extra): Matrix
    {
        foreach ($extra as $x => $row) {
            foreach ($row as $y => $item) {
                if (isset($this->extra[$x])) {
                    $this->extra[$x][$y] = $item;
                } else {
                    $this->extra[$x] = [$y => $item];
                }
            }
        }
        return $this;
    }

    private function setEdge(Point &$point, $x, $y): void
    {
        if (isset($this->map[$x][$y]) && !$point->isAdjoin($this->map[$x][$y])) {
            $point->setAdjoin($this->map[$x][$y]);
            $this->map[$x][$y]->setAdjoin($point);
        }
    }

    private function setAdjoin(Point $point, array $adjoins)
    {
        foreach ($adjoins as $adjoin) {
            $this->setEdge($point, $adjoin[0], $adjoin[1]);
        }
    }

    public function initMap()
    {
        $this->map = [];

        for ($i = 0; $i < $this->row_len; $i++) {
            $this->map[] = [];
            for ($j = 0; $j < $this->col_len; $j++) {
                $this->map[$i][$j] = new Point($i, $j);
            }
        }
    }

    public function initExtra()
    {
        foreach ($this->map as $i => $row) {
            foreach ($row as $j => $point) {
                if (isset($this->extra[$i]) && isset($this->extra[$i][$j])) {
                    $this->setAdjoin($this->map[$i][$j], $this->extra[$i][$j]['adjoins']);
                    $this->map[$i][$j]->setValue($this->extra[$i][$j]['value']);
                    if ($this->extra[$i][$j]['block']) {
                        $this->map[$i][$j]->setBlock($this->extra[$i][$j]['block']);
                        $this->map[$i][$j]->setValue('b');
                    } else {
                        $this->map[$i][$j]->setBlock(0);
                    }
                    if ($this->extra[$i][$j]['price']) {
                        $this->map[$i][$j]->setPrice($this->extra[$i][$j]['price']);
                    } else {
                        $this->map[$i][$j]->setPrice(0);
                    }
                } else {
                    $this->map[$i][$j]->setValue('0');
                    $this->map[$i][$j]->setBlock(0);
                    $this->map[$i][$j]->setPrice(0);
                }
            }
        }
    }

    private function getMaxLength()
    {
        $max_length = 0;
        foreach ($this->map as $row) {
            foreach ($row as $point) {
                $current_length = strlen($point->getValue());
                if (preg_match('/\\e[^m]+m([^\e]+)[^m]+m/', $point->getValue(), $matches)) {
                    $current_length = strlen($matches[1]);
                }
                if ($max_length < $current_length) {
                    $max_length = $current_length;
                }
            }
        }
        return $max_length;
    }

    public function getAdjacentMatrix(array $points): array
    {
        $adjacent_matrix = [];
        $names = [];
        $length = count($points);
        for ($i = 0; $i < $length; $i++) {
            $adjacent_matrix[$i] = [];
            $names[] = 'V' . $points[$i]->x . $points[$i]->y;
            $adjoins = $points[$i]->getAdjoinPoints();
            for ($j = 0; $j < $length; $j++) {
                if ($j == $i) {
                    $adjacent_matrix[$i][$j] = 0;
                    continue;
                }
                if (!Base::inPoints($points[$j], $adjoins)) {
                    $adjacent_matrix[$i][$j] = 'inf';
                    continue;
                }
                $adjacent_matrix[$i][$j] = $points[$j]->getPrice();
            }
        }
        return [$adjacent_matrix, $names];
    }

    public function drawMap()
    {
        $max_length = $this->getMaxLength();
        $block_color = "\e[48;2;" . Color::getColor('purple') . ";38;2;" . Color::getColor('black') . "m%s\e[0m";
        $block_format = sprintf($block_color, "%{$max_length}s");
        echo str_repeat("*", $this->col_len * ($max_length + 1) + 3) . PHP_EOL;
        foreach ($this->map as $row) {
            echo "* ";
            foreach ($row as $index => $point) {
                $value = $point->getValue();
                if ($point->getBlock()) {
                    echo sprintf($block_format, 'B');
                    if (isset($row[$index + 1]) && $row[$index + 1]->getBlock()) {
                        echo sprintf($block_color, ' ');
                    } else {
                        echo " ";
                    }
                } else {
                    if (preg_match('/\\e[^m]+m([^\e]+)[^m]+m/', $value, $matches)) {
                        $format = preg_replace('/(\\e[^m]+m)([^\e]+)([^m]+m)/', "$1%{$max_length}s$3 ", $value);
                        $value = $matches[1];
                        echo sprintf($format, $value);
                    } else {
                        echo sprintf("%{$max_length}s ", "{$value}");
                    }
                }
            }
            echo "*" . PHP_EOL;
        }
        echo str_repeat("*", $this->col_len * ($max_length + 1) + 3) . PHP_EOL;
    }

    public function getDirectionSign(Point $previous_point, Point $next_point): string
    {
        // ↖↑↗←→↙↓↘
        $dx = $previous_point->x == $next_point->x ? 0 : ($previous_point->x < $next_point->x ? 1 : -1);
        $dy = $previous_point->y == $next_point->y ? 0 : ($previous_point->y < $next_point->y ? 1 : -1);
        // FIXME: 优化
        if ($dx == -1 && $dy == -1) {
            return '↖';
        } elseif ($dx == -1 && $dy == 0) {
            return '↑';
        } elseif ($dx == -1 && $dy == 1) {
            return '↗';
        } elseif ($dx == 0 && $dy == -1) {
            return '←';
        } elseif ($dx == 0 && $dy == 0) {
            return '﹢';
        } elseif ($dx == 0 && $dy == 1) {
            return '→';
        } elseif ($dx == 1 && $dy == -1) {
            return '↙';
        } elseif ($dx == 1 && $dy == 0) {
            return '↓';
        } else {
            return '↘';
        }
    }

    public function drawRoads(array $roads)
    {
        if (!$roads) {
            return;
        }
        $this->initExtra();

        $src_point = array_shift($roads);
        $dst_point = array_pop($roads);
        while ($roads) {
            $point = array_shift($roads);
            $next_point = $roads[0] ?? $dst_point;
            $this->setValue($point->x, $point->y, $this->getDirectionSign($point, $next_point), 'red');
        }
        $this->setValue($src_point->x, $src_point->y, 'S', 'green');
        $this->setValue($dst_point->x, $dst_point->y, 'D', 'cyan');
        $this->drawMap();
    }

    public function drawRoadsWithPrice(array $roads)
    {
        if (!$roads) {
            return;
        }
        $this->initExtra();

        foreach ($this->map as $i => $row) {
            foreach ($row as $j => $point) {
                $this->map[$i][$j]->setValue($point->getPrice());
            }
        }

        $src_point = array_shift($roads);
        $dst_point = array_pop($roads);
        while ($roads) {
            $point = array_shift($roads);
            $this->setValue($point->x, $point->y, $point->getPrice(), 'red');
        }
        $this->setValue($src_point->x, $src_point->y, $src_point->getPrice(), 'green');
        $this->setValue($dst_point->x, $dst_point->y, $src_point->getPrice(), 'cyan');
        $this->drawMap();
    }

    public function drawRoadsWithCost(array $roads)
    {
        if (!$roads) {
            return;
        }
        $this->initExtra();

        foreach ($this->map as $i => $row) {
            foreach ($row as $j => $point) {
                $this->map[$i][$j]->setValue($point->cost);
            }
        }

        $src_point = array_shift($roads);
        $dst_point = array_pop($roads);
        while ($roads) {
            $point = array_shift($roads);
            $this->setValue($point->x, $point->y, $point->cost, 'red');
        }
        $this->setValue($src_point->x, $src_point->y, $src_point->cost, 'green');
        $this->setValue($dst_point->x, $dst_point->y, $src_point->cost, 'cyan');
        $this->drawMap();
    }

    public function drawRoadsWithDistance(array $roads)
    {
        if (!$roads) {
            return;
        }
        $this->initExtra();

        foreach ($this->map as $i => $row) {
            foreach ($row as $j => $point) {
                $this->map[$i][$j]->setValue($point->distance);
            }
        }

        $src_point = array_shift($roads);
        $dst_point = array_pop($roads);
        while ($roads) {
            $point = array_shift($roads);
            $this->setValue($point->x, $point->y, $point->distance, 'red');
        }
        $this->setValue($src_point->x, $src_point->y, $src_point->distance, 'green');
        $this->setValue($dst_point->x, $dst_point->y, $dst_point->distance, 'cyan');
        $this->drawMap();
    }

    public function printMap()
    {
        echo str_repeat("=", $this->col_len * (2 + 16) + 1) . PHP_EOL;
        echo "| (x, y, block, price, value)" . str_repeat(' ', $this->col_len * (2 + 16) + 1 - 29) . "|" . PHP_EOL;

        echo str_repeat("=", $this->col_len * (2 + 16) + 1) . PHP_EOL;
        foreach ($this->map as $row) {
            echo "| ";
            foreach ($row as $point) {
                echo "({$point->x}, {$point->y}, {$point->getBlock()}, {$point->getPrice()}, {$point->getValue()}) | ";
            }
            echo PHP_EOL;
        }
        echo str_repeat("=", $this->col_len * (2 + 16) + 1) . PHP_EOL;
    }

    public function printPoints(array $points)
    {
        $this->initExtra();

        echo str_repeat("=", 47) . PHP_EOL;
        echo "| (x, y, block, price, cost, value, distance) |" . PHP_EOL;

        echo str_repeat("=", 47) . PHP_EOL;
        foreach ($points as $point) {
            $output = sprintf(
                "| %-44s |",
                "({$point->x}, {$point->y}, {$point->getBlock()}, {$point->getPrice()}, {$point->cost}, {$point->getValue()}， {$point->distance})"
            );
            echo $output . PHP_EOL;
        }
        echo str_repeat("=", 47) . PHP_EOL;
    }
}
