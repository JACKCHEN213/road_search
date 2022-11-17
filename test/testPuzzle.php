<?php

use common\point\Puzzle;
use strategy\PuzzleSearch;

require_once "../vendor/autoload.php";

$map = json_decode(file_get_contents('../assets/puzzle_3.json'), true);
$map = json_decode(file_get_contents('../assets/puzzle_8.json'), true);

$src_puzzle = new Puzzle($map['simple2']['src_puzzle']);
$dst_puzzle = new Puzzle($map['simple2']['dst_puzzle']);
$puzzle_search = new PuzzleSearch($src_puzzle, $dst_puzzle);
// 八数码的simple1不好搜索
$puzzle_search->bfs();
