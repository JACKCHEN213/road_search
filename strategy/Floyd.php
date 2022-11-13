<?php

namespace strategy;

use common\Point;

class Floyd
{
    public static function getNearestDistance(array $adjacent_matrix, Point $src_point, Point $dst_point)
    {
        for ($i = 0; $i < count($adjacent_matrix); $i++) {
            for ($j = 0; $j < count($adjacent_matrix); $j++) {
                for ($k = 0; $k < count($adjacent_matrix); $k++) {
                    // PASS
                }
            }
        }
    }
}
