<?php

namespace strategy;

use common\point\Point;

class Dijkstra extends Base
{
    protected function popNextPoint(array &$points): Point
    {
        $min_cost = 'inf';
        $ret_index = 0;
        foreach ($points as $index => $point) {
            if ($min_cost == 'inf' || $point->cost < $min_cost) {
                $min_cost = $point->cost;
                $ret_index = $index;
            }
        }
        return array_splice($points, $ret_index, 1)[0];
    }

    public function start()
    {
        echo "*********** start Dijkstra **********" . PHP_EOL;
        $is_find = false;
        $this->open_list[] = $this->src_point;
        while ($this->open_list) {
            $current_point = $this->popNextPoint($this->open_list);
            if ($current_point->equal($this->dst_point)) {
                $is_find = true;
            }
            $this->close_list[] = $current_point;
            $this->calculateCostAndDistance($current_point);
            // 打印搜索过程
            // if (isset($this->open_list[0])) {
            //     $this->render();
            // }
        }
        if (!$is_find) {
            echo "没有找到到达的路径" . PHP_EOL;
        } else {
            $this->map->drawRoadsWithCost($this->map->getRoad($this->dst_point));
        }
        echo "*********** stop Dijkstra **********" . PHP_EOL;
    }

    public function render($current_point = null, $next_point = null)
    {
        echo str_repeat("=", 40) . PHP_EOL;
        echo "| START | PATH | cost |" . PHP_EOL;
        foreach ($this->open_list as $point) {
            $roads = $this->map->getRoad($point);
            $path = [];
            foreach ($roads as $road) {
                $path[] = "({$road->x}, {$road->y})";
            }
            $path = implode('->', $path);
            echo "| ({$roads[0]->x}, {$roads[0]->y}) | {$path} | {$point->cost} |" . PHP_EOL;
        }
        echo str_repeat("=", 40) . PHP_EOL;
        echo PHP_EOL;
    }
}
