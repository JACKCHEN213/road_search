<?php

use common\point\Puzzle;
use strategy\PuzzleSearch;

require_once "../vendor/autoload.php";

$map = json_decode(file_get_contents('../assets/puzzle_3.json'), true);
$map = json_decode(file_get_contents('../assets/puzzle_8.json'), true);
// $map = json_decode(file_get_contents('../assets/puzzle_15.json'), true);

$src_puzzle = new Puzzle($map['simple2']['src_puzzle']);
$dst_puzzle = new Puzzle($map['simple2']['dst_puzzle']);
$puzzle_search = new PuzzleSearch($src_puzzle, $dst_puzzle);
// puzzle_8的simple1要搜索4805次，移动15步，→→↓←←↑→↓→↑←←↓→→
$puzzle_search->bfs();
// puzzle_8的simple1要搜索91次，  移动15步，→→↓←←↑→↓→↑←←↓→→
$puzzle_search->bidirectionalBfs();
// puzzle_8的simple1要搜索448次， 移动15步，→→↓←←↑→↓→↑←←↓→→
$puzzle_search->aStar();
