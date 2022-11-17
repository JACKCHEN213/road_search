<?php

namespace strategy;

use common\point\Puzzle;

class PuzzleSearch
{
    private Puzzle $src_puzzle;
    private Puzzle $dst_puzzle;
    private array $open_list = [];
    private array $close_list = [];

    public function __construct(Puzzle $src_puzzle, Puzzle $dst_puzzle)
    {
        $this->src_puzzle = $src_puzzle;
        $this->dst_puzzle = $dst_puzzle;
    }

    private function inPuzzles(Puzzle $puzzle, array $puzzles): bool
    {
        foreach ($puzzles as $_puzzle) {
            if ($puzzle->equal($_puzzle)) {
                return true;
            }
        }
        return false;
    }

    public function bfs()
    {
        echo "*********** start Puzzle BFS **********" . PHP_EOL;
        $is_find = false;
        $this->open_list[] = $this->src_puzzle;
        $search_time = 0;
        while ($this->open_list) {
            $current_puzzle = array_shift($this->open_list);
            if (!$current_puzzle instanceof Puzzle) {
                // 这一步是IDEA类型提示、跳转
                continue;
            }
            $this->close_list[] = $current_puzzle;
            if ($current_puzzle->equal($this->dst_puzzle)) {
                $is_find = true;
                $this->dst_puzzle = $current_puzzle;
                echo "搜索到结果了！" . PHP_EOL;
                break;
            }
            $pos = $current_puzzle->getZeroPos();
            if ($pos[0] == -1 || $pos[1] == -1) {
                break;
            }
            $moves = ['left' => 1, 'right' => 1, 'top' => 1, 'bottom' => 1];
            if ($pos[0] == 0) {
                unset($moves['top']);
            } elseif ($pos[0] == $current_puzzle->getLength() - 1) {
                unset($moves['bottom']);
            }
            if ($pos[1] == 0) {
                unset($moves['left']);
            } elseif ($pos[1] == $current_puzzle->getLength() - 1) {
                unset($moves['right']);
            }
            foreach ($moves as $direction => $placeholder) {
                $child_puzzle = $current_puzzle->moving($direction, $pos);
                if (
                    $this->inPuzzles($child_puzzle, $this->open_list)
                    || $this->inPuzzles($child_puzzle, $this->close_list)
                ) {
                    continue;
                }
                $child_puzzle->level = $current_puzzle->level + 1;
                $child_puzzle->parent = $current_puzzle;
                $current_puzzle->setChild($child_puzzle);
                $this->open_list[] = $child_puzzle;
            }
            $search_time++;
            echo "搜索第{$search_time}次，层级{$current_puzzle->level}" . PHP_EOL;
        }
        if (!$is_find) {
            echo "移动不到目标位置" . PHP_EOL;
        } else {
            $roads = $this->dst_puzzle->getRoads();
            echo "需要移动" . count($roads) . "步" . PHP_EOL;
            $this->printRoads($roads);
        }
        echo "*********** stop Puzzle BFS **********" . PHP_EOL;
    }

    private function printRoads(array $roads)
    {
        foreach ($roads as $index => $puzzle) {
            if ($puzzle instanceof Puzzle) {
                echo $puzzle->getPuzzleInfo() . PHP_EOL;
                if ($index < count($roads) - 1) {
                    echo ($index + 1) . "=>" . PHP_EOL;
                }
            }
        }
    }
}
