<?php

require_once "../vendor/autoload.php";

use common\map\Matrix;

require_once "../vendor/autoload.php";

$asset_8x8 = json_decode(file_get_contents('../assets/8x8.json'), true);
$extra = $asset_8x8['simple1'];

$map = new Matrix(8, 8, $extra);

echo <<<EOF
<!DOCTYPE html>
<html lang="zh">
  <head>
    <title>路径搜索</title>
    <style>
      * {
        margin: 0;
        padding: 0;
      }
      #map {
        padding: 50px 100px;
        background-color: #ff0;
      }
      .row {
        display: flex;
      }
      .point {
        display: inline-flex;
        width: 50px;
        height: 30px;
        border: 1px solid black;
        justify-content: center;
        align-items: center;
      }
      #control {
        height: 100px;
        display: flex;
        padding: 20px 50px;
        background-color: red;
        align-items: center;
      }
      .btn {
        height: 40px;
        padding: 0 10px;
        margin: 0 20px
      }
    </style>
  </head>
  <body>
EOF;


echo '<div id="container">';
echo '<div id="map">';
foreach ($map->getMap() as $x => $row) {
    echo '<div class="row" id="row' . $x . '">';
    foreach ($row as $y => $point) {
        echo '<div class="point" id="point' . $x . $y . '">('
            . $x . ',' . $y . ')' . '</div>';
    }
    echo '</div>';
}
echo '</div>';
// 控制按钮
echo <<<EOF
<div id="control">
  <button class="btn">开始</button>
  <button class="btn">重新开始</button>
  <button class="btn">下一步</button>
  <button class="btn">持续运行</button>
</div>
EOF;

echo '</div>';

echo <<<EOF
  </body>
</html>
EOF;
