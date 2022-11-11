<?php

namespace strategy;

use common\Map;
use common\Point;

abstract class Base
{
    protected array $open_list = [];
    protected Point $src_point;
    protected Point $dst_point;
    protected array $close_list = [];
    protected Map $map;

    public function __construct(Point $src_point, Point $dst_point, Map $map)
    {
        $this->src_point = $src_point;
        $this->dst_point = $dst_point;
        $this->map = $map;
    }

    protected function popNextPoint(array &$points)
    {
        return array_shift($points);
    }

    /**
     * 曼哈顿距离
     */
    protected function getManhattanDistance(Point $point)
    {
        return abs($point->x - $this->dst_point->x) + abs($point->y - $this->dst_point->y);
    }

    /**
     * 对角线距离
     */
    protected function getDiagonalDistance(Point $point)
    {
        $dx = abs($point->x - $this->dst_point->x);
        $dy = abs($point->y - $this->dst_point->y);
        $d = 1;
        $d2 = sqrt(1 + 1);
        return $d * ($dx + $dy) + ($d2 - 2 * $d) * min($dx, $dy);
        return $d * max($dx, $dy) + ($d2 - $d) * min($dx, $dy);
        if ($dx > $dy) {
            return $d * ($dx - $dy) + $d2 * $dy;
        } else {
            return $d * ($dy - $dx) + $d2 * $dx;
        }
        # Patrick Lester if(dx > dy) (D * (dx - dy) + D2 * dy) else (D * (dy - dx) + D2 * dx)
    }

    /**
     * 欧几里得距离
     */
    protected function getEuclideanDistance(Point $point)
    {
        $dx = abs($point->x - $this->dst_point->x);
        $dy = abs($point->y - $this->dst_point->y);
        $d = 1;
        return $d * sqrt($dx * $dx + $dy * $dy);
    }


    public function inPoints(Point $detect_point, array $points): bool
    {
        foreach ($points as $point) {
            if ($detect_point->equal($point)) {
                return true;
            }
        }
        return false;
    }

    abstract public function start();

    protected function render(Point $current_point, Point $next_point)
    {
        $this->map->initExtra();
        $this->map->setValues($this->open_list, 'O', 'yellow');
        $this->map->setValues($this->close_list, 'C', 'magenta');
        $this->map->setValue($this->src_point->x, $this->src_point->y, 'S', 'green');
        $this->map->setValue($this->dst_point->x, $this->dst_point->y, 'D', 'cyan');
        $this->map->setValue(
            $current_point->x,
            $current_point->y,
            $this->map->getDirectionSign($current_point, $next_point),
            'red'
        );
        $this->map->setValue($next_point->x, $next_point->y, 'N', 'dark green');
        $this->map->drawMap();
    }
}
