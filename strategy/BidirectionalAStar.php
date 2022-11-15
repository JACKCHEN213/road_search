<?php

namespace strategy;

use common\Map;
use common\Point;

class BidirectionalAStar extends AStar
{
    private array $dst_open_list;
    private array $dst_close_list;

    public function __construct(Point $src_point, Point $dst_point, Map $map)
    {
        parent::__construct($src_point, $dst_point, $map);
        $this->dst_open_list = [];
        $this->dst_close_list = [];
    }

    public function start()
    {
        echo "*********** start Bidirectional AStar **********" . PHP_EOL;
        $is_find = false;
        $this->open_list[] = $this->src_point;
        $this->dst_open_list[] = $this->dst_point;
        while ($this->open_list && $this->dst_open_list) {
            $current_point = $this->popNextPoint($this->open_list);
            $current_point->children = [];
            $dst_current_point = $this->popNextPoint($this->dst_open_list);
            $dst_current_point->children = [];
            $this->close_list[] = $current_point;
            $this->dst_close_list[] = $dst_current_point;

            $adjoin_points = $current_point->getAdjoinPoints();
            $dst_adjoin_points = $dst_current_point->getAdjoinPoints();
            foreach ($adjoin_points as $adjoin_point) {
                if ($this->inPoints($adjoin_point, $this->close_list)) {
                    continue;
                }
                if (!$this->inPoints($adjoin_point, $this->open_list)) {
                    $adjoin_point->parent = $current_point;
                    $current_point->children[] = $adjoin_point;
                    $adjoin_point->cost = $current_point->cost + $adjoin_point->getPrice();
                    // $adjoin_point->distance = $current_point->distance + $this->getManhattanDistance($adjoin_point);
                    $adjoin_point->distance = $current_point->distance + $this->getDiagonalDistance($adjoin_point);
                    // $adjoin_point->distance = $current_point->distance + $this->getEuclideanDistance($adjoin_point);
                    $this->open_list[] = $adjoin_point;
                } else {
                    $new_cost = $current_point->cost + $adjoin_point->getPrice();
                    // $new_distance = $current_point->distance + $this->getManhattanDistance($adjoin_point);
                    $new_distance = $current_point->distance + $this->getDiagonalDistance($adjoin_point);
                    // $new_distance = $current_point->distance + $this->getEuclideanDistance($adjoin_point);
                    if ($new_distance + $new_cost < $adjoin_point->cost + $adjoin_point->distance) {
                        $adjoin_point->cost = $new_cost;
                        $adjoin_point->distance = $new_distance;
                        $adjoin_point->parent = $current_point;
                        $current_point->children[] = $adjoin_point;
                    }
                }
            }
            foreach ($dst_adjoin_points as $dst_adjoin_point) {
                if ($this->inPoints($dst_adjoin_point, $this->dst_close_list)) {
                    continue;
                }
                if (!$this->inPoints($dst_adjoin_point, $this->dst_open_list)) {
                    $dst_adjoin_point->parent = $dst_current_point;
                    $dst_current_point->children[] = $dst_adjoin_point;
                    $dst_adjoin_point->cost = $dst_current_point->cost + $dst_adjoin_point->getPrice();
                    // $dst_adjoin_point->distance = $dst_current_point->distance
                    // + $this->getManhattanDistance($dst_adjoin_point);
                    $dst_adjoin_point->distance = $dst_current_point->distance
                        + $this->getDiagonalDistance($dst_adjoin_point);
                    // $dst_adjoin_point->distance = $dst_current_point->distance
                    // + $this->getEuclideanDistance($dst_adjoin_point);
                    $this->dst_open_list[] = $dst_adjoin_point;
                } else {
                    $new_cost = $dst_current_point->cost + $dst_adjoin_point->getPrice();
                    // $new_distance = $dst_current_point->distance + $this->getManhattanDistance($dst_adjoin_point);
                    $new_distance = $dst_current_point->distance + $this->getDiagonalDistance($dst_adjoin_point);
                    // $new_distance = $dst_current_point->distance + $this->getEuclideanDistance($dst_adjoin_point);
                    if ($new_distance + $new_cost < $dst_adjoin_point->cost + $dst_adjoin_point->distance) {
                        $dst_adjoin_point->cost = $new_cost;
                        $dst_adjoin_point->distance = $new_distance;
                        $dst_adjoin_point->parent = $dst_current_point;
                        $dst_current_point->children[] = $dst_adjoin_point;
                    }
                }
            }
            // 判断open_list是否重叠
            foreach ($this->open_list as $point) {
                if ($this->inPoints($point, $this->dst_open_list)) {
                    /**
                     * TODO: 队首和队尾怎么拼接
                     * 队首和队尾在open_list
                     * 队首在open_list、队尾在close_list
                     * 队首在close_list、队尾在open_list
                     */
                    $is_find = true;
                    var_dump($point->getPointInfo());
                    $this->map->drawRoadsWithCost($this->map->getRoad($point));
                    $this->map->drawRoadsWithCost($this->map->getRoad($dst_current_point));
                    return;
                    break;
                }
            }
        }
        if (!$is_find) {
            echo "没有找到到达的路径" . PHP_EOL;
        } else {
            $this->map->drawRoadsWithCost($this->map->getRoad($this->dst_point));
        }
        echo "*********** stop Bidirectional AStar **********" . PHP_EOL;
    }
}
