<?php

namespace strategy;

class AStar extends Base
{
    protected function popNextPoint(array &$points)
    {
        $min_priority = 'inf';
        $ret_index = 0;
        foreach ($points as $index => $point) {
            if ($min_priority == 'inf' || ($point->distance + $point->cost < $min_priority)) {
                // F(N) = G(N) + H(N)
                $min_priority = $point->cost + $point->distance;
                $ret_index = $index;
            }
        }
        return array_splice($points, $ret_index, 1)[0];
    }

    public function start()
    {
        echo "*********** start AStar **********" . PHP_EOL;
        $is_find = false;
        $this->open_list[] = $this->src_point;
        while ($this->open_list) {
            $current_point = $this->popNextPoint($this->open_list);
            if ($current_point->equal($this->dst_point)) {
                $is_find = true;
                break;
            }
            $this->close_list[] = $current_point;
            $adjoin_points = $current_point->getAdjoinPoints();
            foreach ($adjoin_points as $adjoin_point) {
                if ($this->inPoints($adjoin_point, $this->close_list)) {
                    continue;
                }
                if (!$this->inPoints($adjoin_point, $this->open_list)) {
                    $adjoin_point->parent = $current_point;
                    $adjoin_point->cost = $current_point->cost + $adjoin_point->getPrice();
                    $adjoin_point->distance = $current_point->distance + $this->getManhattanDistance($adjoin_point);
                    $this->open_list[] = $adjoin_point;
                } else {
                    $new_cost = $current_point->cost + $adjoin_point->getPrice();
                    $new_distance = $current_point->distance + $this->getManhattanDistance($adjoin_point);
                    if ($new_distance + $new_cost < $adjoin_point->cost + $adjoin_point->distance) {
                        $adjoin_point->cost = $new_cost;
                        $adjoin_point->distance = $new_distance;
                        $adjoin_point->parent = $current_point;
                    }
                }
            }
        }
        if (!$is_find) {
            echo "没有找到到达的路径" . PHP_EOL;
        } else {
            $this->map->drawRoadsWithCost($this->map->getRoad($this->dst_point));
        }
        echo "*********** stop AStar **********" . PHP_EOL;
    }
}
