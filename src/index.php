<?php

require_once "../vendor/autoload.php";

use common\map\Matrix;

require_once "../vendor/autoload.php";

$asset_8x8 = json_decode(file_get_contents('../assets/8x8.json'), true);
$extra = $asset_8x8['simple1'];

$map = new Matrix(8, 8, $extra);

echo '<div>';
foreach ($map->getMap() as $row) {
    echo '<div style="display: flex">';
    foreach ($row as $point) {
        echo '<div style="display: inline-flex;width: 50px;height: 30px;border: 1px solid black; justify-content: center; align-items: center;">('
            . $point->x . ',' . $point->y . ')' . '</div>';
    }
    echo '</div>';
}
echo '</div>';
