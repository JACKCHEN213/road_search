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
                    $adjoin_point->cost = $current_point->cost + $adjoin_point->getPrice();
                }
            }
            // 打印搜索过程
            // if (isset($this->open_list[0])) {
            //     $this->render($current_point, $this->open_list[0]);
            // }
        }
        if (!$is_find) {
            echo "没有找到到达的路径" . PHP_EOL;
        } else {
            $this->map->drawRoadsWithCost($this->map->getRoad($this->dst_point));
        }
        echo "*********** stop BFS **********" . PHP_EOL;
    }
}
