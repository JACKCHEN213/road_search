<?php

namespace strategy\ex;

use common\map\Map;
use common\point\Point;
use strategy\BreadthFirstSearch;

class BreadthFirstSearchEx extends BreadthFirstSearch
{
    public bool $is_find = false;

    public function __construct(Point $src_point, Point $dst_point, Map $map)
    {
        parent::__construct($src_point, $dst_point, $map);
        $this->open_list[] = $this->src_point;
    }

    public function next()
    {
        if ($this->is_find) {
            return true;
        }
        if (!$this->open_list && !$this->is_find) {
            return false;
        }
        $current_point = $this->popNextPoint($this->open_list);
        $this->close_list[] = $current_point;
        if ($current_point->equal($this->dst_point)) {
            $this->is_find = true;
            return true;
        }
        $this->calculateCostAndDistance($current_point);
        return ['open_list' => $this->open_list, 'close_list' => $this->close_list];
    }
}
