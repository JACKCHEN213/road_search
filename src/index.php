<?php

require_once "../vendor/autoload.php";

use common\map\Matrix;

$asset_8x8 = json_decode(file_get_contents('../assets/8x8.json'), true);
$extra = $asset_8x8['simple1'];

$map = new Matrix(8, 8, $extra);
$src_point = $map->getPoint(0, 0);
$dst_point = $map->getPoint(7, 5);

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
  <div class="point_center">初始化值</div>
  <div class="point_right">过程</div>
</div>
EOF;

// 控制按钮
echo <<<EOF
<div id="control">
  <button id="start_btn" class="btn primary">开始</button>
  <button id="restart_btn" class="btn success">重新开始</button>
  <button id="next_btn" class="btn info">下一步</button>
  <button id="running_btn" class="btn warning">持续运行</button>
</div>
EOF;

echo '</div>';

echo <<<EOF
  <script type="application/javascript">
    let inputs = document.getElementsByClassName('input');
    for (let i = 0; i < inputs.length; i++) {
      inputs[i].classList.add('disable');
      inputs[i].disabled = 'true';
    }

    let start_btn = document.getElementById('start_btn');
    let restart_btn = document.getElementById('restart_btn');
    let next_btn = document.getElementById('next_btn');
    let running_btn = document.getElementById('running_btn');
    start_btn.addEventListener('mousedown', function(e) {
        start_btn.innerHTML = 'click';
        start_btn.classList.remove('primary');
        start_btn.classList.add('disable');
    });
  </script>
  </body>
</html>
EOF;
