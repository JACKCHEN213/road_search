<?php

namespace strategy;

use common\Point;

class GreedyBestFirstSearch extends Base
{
    protected function popNextPoint(array &$points)
    {
        $min_distance = 'inf';
        $ret_index = 0;
        foreach ($points as $index => $point) {
            if ($min_distance == 'inf' || $point->distance < $min_distance) {
                $min_distance = $point->distance;
                $ret_index = $index;
            }
        }
        return array_splice($points, $ret_index, 1)[0];
    }

    public function start()
    {
        echo "*********** start GBFS **********" . PHP_EOL;
        $is_find = false;
        $this->open_list[] = $this->src_point;
        while ($this->open_list) {
            $current_point = $this->popNextPoint($this->open_list);
            if ($current_point->equal($this->dst_point)) {
                // $this->map->drawRoadsWithCost($this->map->getRoad($current_point));
                $is_find = true;
                // break;
            }
            $this->close_list[] = $current_point;
            $adjoin_points = $current_point->getAdjoinPoints();
            foreach ($adjoin_points as $adjoin_point) {
                $flag = false;
                if ($this->inPoints($adjoin_point, $this->open_list)) {
                    $flag = true;
                }
                if ($this->inPoints($adjoin_point, $this->close_list)) {
                    $flag = true;
                }
                if (!$flag) {
                    // 加入open_list
                    $this->open_list[] = $adjoin_point;
                    $adjoin_point->parent = $current_point;
                    $adjoin_point->cost = $adjoin_point->getPrice() + $current_point->cost;
                    $adjoin_point->distance = $current_point->distance + $this->getManhattanDistance($adjoin_point);
                    // $adjoin_point->distance = $current_point->distance + $this->getDiagonalDistance($adjoin_point);
                    // $adjoin_point->distance = $current_point->distance + $this->getEuclideanDistance($adjoin_point);
                }
            }
        }
        if (!$is_find) {
            echo "没有找到到达的路径" . PHP_EOL;
        } else {
            $this->map->drawRoadsWithCost($this->map->getRoad($this->dst_point));
        }
        echo "*********** stop GBFS **********" . PHP_EOL;
    }
}
