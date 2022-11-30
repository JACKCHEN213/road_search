<?php

namespace strategy;

use common\point\Point;

/**
 * A星算法优化
 * 采用布兰森汉姆算法预先判断两点是否可以直接通行，可通行就直接返回两点的直线路径，不可直接通行再采用A星算法寻路，提高寻路效率
 * Class Bresenham
 * @package strategy
 */
class Bresenham extends AStar
{
    private function judgeDirectRoads(Point $point): bool
    {
        $dx = abs($point->x - $this->dst_point->x);
        $dy = -abs($point->y - $this->dst_point->y);
        $sx = $this->dst_point->x > $point->x ? 1 : -1;
        $sy = $this->dst_point->y > $point->y ? 1 : -1;
        $err = $dx + $dy;
        $current_point = $point;

        // 判断是否直连
        while (true) {
            if ($current_point->equal($this->dst_point)) {
                break;
            }
            $e2 = $err * 2;
            if ($e2 >= $dy) {
                $err += $dy;
                $current_point = $this->map->getPoint($current_point->x + $sx, $current_point->y);
                if (is_null($current_point) || $current_point->getBlock()) {
                    return false;
                }
            }
            if ($e2 <= $dx) {
                $err += $dx;
                $current_point = $this->map->getPoint($current_point->x, $current_point->y + $sy);
                if (is_null($current_point) || $current_point->getBlock()) {
                    return false;
                }
            }
        }
        $err = $dx + $dy;
        $current_point = $point;
        // 构建父节点
        while (true) {
            if ($current_point->equal($this->dst_point)) {
                break;
            }
            $tmp_point = null;
            $e2 = $err * 2;
            if ($e2 >= $dy) {
                $err += $dy;
                $tmp_point = $this->map->getPoint($current_point->x + $sx, $current_point->y);
                if (is_null($tmp_point) || $tmp_point->getBlock()) {
                    return false;
                }
            }
            if ($tmp_point) {
                $tmp_point->cost = $current_point->cost + $tmp_point->getPrice();
                $tmp_point->parent = $current_point;
                $current_point = $tmp_point;
            }
            if ($e2 <= $dx) {
                $err += $dx;
                $tmp_point = $this->map->getPoint($current_point->x, $current_point->y + $sy);
                if (is_null($tmp_point) || $tmp_point->getBlock()) {
                    return false;
                }
            }
            if ($tmp_point) {
                $tmp_point->cost = $current_point->cost + $tmp_point->getPrice();
                $tmp_point->parent = $current_point;
                $current_point = $tmp_point;
            }
        }
        return true;
    }

    public function start()
    {
        echo "*********** start Bresenham **********" . PHP_EOL;
        $is_find = false;
        $this->open_list[] = $this->src_point;
        while ($this->open_list) {
            $current_point = $this->popNextPoint($this->open_list);
            // 采用布兰森汉姆算法预先判断两点是否可以直接通行
            if ($this->judgeDirectRoads($current_point)) {
                $is_find = true;
                break;
            }
            if ($current_point->equal($this->dst_point)) {
                $is_find = true;
                break;
            }
            $this->close_list[] = $current_point;
            $this->calculateCostAndDistance($current_point);
        }
        if (!$is_find) {
            echo "没有找到到达的路径" . PHP_EOL;
        } else {
            $this->map->drawRoadsWithCost($this->map->getRoad($this->dst_point));
        }
        echo "*********** stop Bresenham **********" . PHP_EOL;
    }
}
