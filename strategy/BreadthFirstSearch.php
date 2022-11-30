<?php

namespace strategy;

class BreadthFirstSearch extends Base
{
    public function start()
    {
        echo "*********** start BFS **********" . PHP_EOL;
        $is_find = false;
        $this->open_list[] = $this->src_point;
        while ($this->open_list) {
            $current_point = $this->popNextPoint($this->open_list);
            if ($current_point->equal($this->dst_point)) {
                $is_find = true;
            }
            // 当前point加入close_list中
            $this->close_list[] = $current_point;
            $this->calculateCostAndDistance($current_point);
        }
        if (!$is_find) {
            echo "没有找到到达的路径" . PHP_EOL;
        } else {
            $this->map->drawRoadsWithCost($this->map->getRoad($this->dst_point));
        }
        echo "*********** stop BFS **********" . PHP_EOL;
    }
}
