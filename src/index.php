<?php

require_once "../vendor/autoload.php";

use common\map\Matrix;

require_once "../vendor/autoload.php";

$asset_8x8 = json_decode(file_get_contents('../assets/8x8.json'), true);
$extra = $asset_8x8['simple1'];

$map = new Matrix(8, 8, $extra);

echo "<table>";
foreach ($map->getMap() as $row) {
    foreach ($row as $point) {
        echo "x = $point->x<br/>";
    }
}
echo "<table/>";
