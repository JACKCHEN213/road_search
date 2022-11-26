<?php

require_once "../vendor/autoload.php";

use common\map\Matrix;

$asset_8x8 = json_decode(file_get_contents('../assets/8x8.json'), true);
$extra = $asset_8x8['simple1'];

$map = new Matrix(8, 8, $extra);

echo <<<EOF
<!DOCTYPE html>
<html lang="zh">
  <head>
    <title>路径搜索</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/btn.css">
    <link rel="stylesheet" type="text/css" href="css/input.css">
  </head>
  <body>
EOF;


echo '<div id="container">';
echo '<div id="map">';
foreach ($map->getMap() as $x => $row) {
    echo '<div class="row" id="row' . $x . '">';
    foreach ($row as $y => $point) {
        echo '<div class="node" id="node' . $x . $y . '">('
            . $x . ',' . $y . ')' . '</div>';
    }
    echo '</div>';
}
echo '</div>';
// 输入框
echo <<<EOF
<div id="points">
  <div class="points_select">
    <div class="point">
      <span>起始点</span>
      <p><span>x:&nbsp;</span><input name="src_x" type="text" /></p>
      <p><span>y:&nbsp;</span><input name="src_y" type="text" /></p>
    </div>
    <div class="point">
      <span>终止点</span>
      <p><span>x:&nbsp;</span><input name="dst_x" type="text" /></p>
      <p><span>y:&nbsp;</span><input name="dst_y" type="text" /></p>
    </div>
  </div>
</div>
EOF;

// 控制按钮
echo <<<EOF
<div id="control">
  <button class="btn primary">开始</button>
  <button class="btn success">重新开始</button>
  <button class="btn info">下一步</button>
  <button class="btn warning">持续运行</button>
</div>
EOF;

echo '</div>';

echo <<<EOF
  <script type="application/javascript" src="js/index.js"></script>
  </body>
</html>
EOF;
