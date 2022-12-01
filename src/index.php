<?php

require_once "../vendor/autoload.php";

use common\map\Matrix;
use strategy\ex\BreadthFirstSearchEx;

$asset_8x8 = json_decode(file_get_contents('../assets/8x8.json'), true);
$extra = $asset_8x8['simple1'];

$map = new Matrix(8, 8, $extra);
$src_point = $map->getPoint();
$dst_point = $map->getPoint(7, 5);
$breadth_first_search = new BreadthFirstSearchEx($src_point, $dst_point, $map);

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
    <link rel="stylesheet" type="text/css" href="css/road.css">
    <script src="js/Button.js"></script>
    <script src="js/Draw.js"></script>
    <script src="js/Road.js"></script>
  </head>
  <body>
EOF;


echo '<div id="container">';
echo '<div id="map">';
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
        <span class="l_text">起始点</span>
      </div>
      <div class="l_item">
        <div class="l_cube road_node"></div>
        <span class="l_text">路径</span>
      </div>
      <div class="l_item">
        <div class="l_cube dst_node"></div>
        <span class="l_text">终止点</span>
      </div>
      <div class="l_item">
        <div class="l_cube block_node"></div>
        <span class="l_text">阻塞点</span>
      </div>
      <div class="l_item">
        <div class="l_cube open_node"></div>
        <span class="l_text">open_list</span>
      </div>
      <div class="l_item">
        <div class="l_cube close_node"></div>
        <span class="l_text">close_list</span>
      </div>
    </div>
  </div>
  <div class="point_right">
    <span>结果路径</span>
    <div id="roads" class="roads"></div>
  </div>
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
    let button = new Button();
    let draw = new Draw();
    let road = new Road();
    let inputs = document.getElementsByClassName('input');
    for (let i = 0; i < inputs.length; i++) {
      inputs[i].classList.add('disable');
      inputs[i].disabled = true;
    }
    let map = JSON.parse('{$map->getMap(true)}');
    let src_node = JSON.parse('{$src_point->toJson()}');
    let dst_node = JSON.parse('{$dst_point->toJson()}');
    draw.drawMap(map);
    draw.drawSrcAndDst(src_node, dst_node);
    draw.drawRoads(JSON.parse('{$map->getRoad($dst_point, true)}'), [src_node, dst_node]);
    road.drawRoads(JSON.parse('{$map->getRoad($dst_point, true)}'));
    
    button.setStyle();
    button.setStyle('enable_start');
    button.startBtn.onclick = function (ev) {
        button.setStyle('enable_all');
        button.setStyle('disable_start');
    }
    
    button.restartBtn.onclick = function(ev) {
        button.setStyle('enable_all');
        button.setStyle('disable_start');
    }
    
    button.nextBtn.onclick = function(ev) {
        //
    }
    
    button.runningBtn.onclick = function(ev) {
        button.setStyle('disable_next');
    }
  </script>
  </body>
</html>
EOF;
