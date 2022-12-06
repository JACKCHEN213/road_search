<?php

namespace common\map;

use common\point\Point;
use common\Color;

abstract class Map
{
    protected array $map = [];
    protected array $extra;

    public function __construct(array $extra = [])
    {
        $this->extra = $extra;
    }

    abstract public function initMap();

    public function getMap($is_json = false)
    {
        if ($is_json) {
            return json_encode($this->map);
        }
        return $this->map;
    }

    public function setMap(array $map): Map
    {
        $this->map = $map;
        return $this;
    }

    abstract public function initExtra();

    public function setExtra(array $extra): Map
    {
        $this->extra = $extra;
        return $this;
    }

    public function getRoad(Point $point, $is_json = false)
    {
        $roads = [$point];
        while ($point->parent) {
            $point = $point->parent;
            $roads[] = $point;
        }
        if ($is_json) {
            return json_encode(array_reverse($roads));
        }
        return array_reverse($roads);
    }

    public function getPoint($x = 0, $y = 0): ?Point
    {
        if (!isset($this->map[$x][$y])) {
            return null;
        }
        return $this->map[$x][$y];
    }

    public function setValue($x, $y, $sign = '0', $color = null): Map
    {
        if (!isset($this->map[$x][$y])) {
            return $this;
        }
        $color = strval($color);
        if ($color) {
            $color = Color::getColor($color) ?: $color;
            $this->map[$x][$y]->setValue("\e[38;2;{$color}m{$sign}\e[0m");
        } else {
            $this->map[$x][$y]->setValue($sign);
        }
        return $this;
    }

    public function setValues(array $points, $sign = '0', $color = null): Map
    {
        foreach ($points as $point) {
            $this->setValue($point->x, $point->y, $sign, $color);
        }
        return $this;
    }

    abstract public function getAdjacentMatrix(array $points);

    abstract public function getDirectionSign(Point $previous_point, Point $next_point);

    abstract public function drawMap();

    abstract public function drawRoads(array $roads);

    abstract public function drawRoadsWithPrice(array $roads);

    abstract public function drawRoadsWithCost(array $roads);

    abstract public function drawRoadsWithDistance(array $roads);

    abstract public function printMap();

    abstract public function printPoints(array $points);
}
