<?php

require_once "../vendor/autoload.php";

use common\map\Matrix;
use strategy\BreadthFirstSearch;

$asset_8x8 = json_decode(file_get_contents('../assets/8x8.json'), true);
$extra = $asset_8x8['simple1'];

$map = new Matrix(8, 8, $extra);
$src_point = $map->getPoint(0, 0);
$dst_point = $map->getPoint(7, 5);
ob_start();
$breadth_first_search = new BreadthFirstSearch($src_point, $dst_point, $map);
$breadth_first_search->start();
ob_get_contents();
ob_end_clean();
$roads = $map->getRoad($dst_point);

echo <<<EOF
<!DOCTYPE html>
<html lang="zh">
  <head>
    <title>路径搜索</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/btn.css">
    <link rel="stylesheet" type="text/css" href="css/input.css">
    <link rel="stylesheet" type="text/css" href="css/node.css">
    <link rel="stylesheet" type="text/css" href="css/legend.css">
  </head>
  <body>
EOF;


echo '<div id="container">';
echo '<div id="map">';
foreach ($map->getMap() as $x => $row) {
    echo '<div class="row" id="row' . $x . '">';
    foreach ($row as $y => $point) {
        echo '<div class="node';
        if ($src_point->equal($point)) {
            echo ' src_node';
        } elseif ($dst_point->equal($point)) {
            echo ' dst_node';
        } elseif ($point->getBlock()) {
            echo ' block_node';
        } elseif (\strategy\Base::inPoints($point, $roads)) {
            echo ' road_node';
        }
        echo '" id="node' . $x . $y . '">' . $point->getPrice() . '</div>';
    }
    echo '</div>';
}
echo '</div>';
// 输入框
echo <<<EOF
<div id="points">
  <div class="points_left">
    <div class="point">
      <span class="text">起始点</span>
      <div class="input_bar">
        <p>
          <span>x:&nbsp;</span>
          <input class="input" autocomplete="off" name="src_x" type="text" value="$src_point->x" />
        </p>
        <p>
          <span>y:&nbsp;</span>
          <input class="input" autocomplete="off" name="src_y" type="text" value="$src_point->y" />
        </p>
      </div>
    </div>
    <div class="point">
      <span class="text">终止点</span>
      <div class="input_bar">
        <p>
          <span>x:&nbsp;</span>
          <input class="input" autocomplete="off" name="src_x" type="text" value="$dst_point->x" />
        </p>
        <p>
          <span>y:&nbsp;</span>
          <input class="input" autocomplete="off" name="src_y" type="text" value="$dst_point->y" />
        </p>
      </div>
    </div>
  </div>
  <div class="point_center">
    <span>图例</span>
    <div class="legend">
      <div class="l_item">
        <div class="l_cube src_node"></div>
        <span>起始点</span>
      </div>
      <div class="l_item">
        <div class="l_cube dst_node"></div>
        <span>终止点</span>
      </div>
      <div class="l_item">
        <div class="l_cube block_node"></div>
        <span>阻塞点</span>
      </div>
      <div class="l_item">
        <div class="l_cube open_node"></div>
        <span>open_list</span>
      </div>
      <div class="l_item">
        <div class="l_cube close_node"></div>
        <span>close_list</span>
      </div>
    </div>
  </div>
  <div class="point_right">过程</div>
</div>
EOF;

// 控制按钮
echo <<<EOF
<div id="control">
  <button id="start_btn" class="btn primary">开始</button>
  <button id="restart_btn" class="btn success">重新开始</button>
  <button id="next_btn" class="btn danger">下一步</button>
  <button id="running_btn" class="btn warning">持续运行</button>
</div>
EOF;

echo '</div>';

echo <<<EOF
  <script type="application/javascript">
    let inputs = document.getElementsByClassName('input');
    for (let i = 0; i < inputs.length; i++) {
      inputs[i].classList.add('disable');
      inputs[i].disabled = true;
    }
    
    for (let btn of document.getElementsByClassName('btn')) {
        btn.className  = 'btn disable';
        btn.disabled = true;
    }
    let start_btn = document.getElementById('start_btn');
    let restart_btn = document.getElementById('restart_btn');
    let next_btn = document.getElementById('next_btn');
    let running_btn = document.getElementById('running_btn');
    start_btn.className = 'btn primary';
    start_btn.disabled = false;
    start_btn.onclick = function () {
        start_btn.className  = 'btn disable';
        start_btn.disabled = true;
    }
  </script>
  </body>
</html>
EOF;
