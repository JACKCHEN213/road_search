<?php

namespace strategy\ex;

use common\map\Map;
use common\point\Point;
use strategy\BreadthFirstSearch;

class BreadthFirstSearchEx extends BreadthFirstSearch
{
    /**
     * @var string [searching find not_find]
     */
    public string $search_status = 'searching';
    public int $seq = 0;

    public function __construct(Point $src_point, Point $dst_point, Map $map)
    {
        parent::__construct($src_point, $dst_point, $map);
        $this->open_list[] = $this->src_point;
    }

    public function next(): string
    {
        $this->seq++;
        if (!$this->open_list) {
            $this->search_status = 'not_find';
        }
        if (in_array($this->search_status, ['find', 'not_find'])) {
            return $this->search_status;
        }
        $current_point = $this->popNextPoint($this->open_list);
        $this->close_list[] = $current_point;
        if ($current_point->equal($this->dst_point)) {
            $this->search_status = 'find';
            return $this->search_status;
        }
        $this->calculateCostAndDistance($current_point);
        return $this->search_status;
    }

    public function getOpenList($is_json = false)
    {
        if ($is_json) {
            return json_encode($this->open_list);
        }
        return $this->open_list;
    }

    public function getCloseList($is_json = false)
    {
        if ($is_json) {
            return json_encode($this->close_list);
        }
        return $this->close_list;
    }
}
