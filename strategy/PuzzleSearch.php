<?php

namespace strategy;

use common\point\Puzzle;

class PuzzleSearch
{
    private ?Puzzle $src_puzzle = null;
    private ?Puzzle $dst_puzzle = null;

    public function __construct(Puzzle $src_puzzle, Puzzle $dst_puzzle)
    {
        // 判断有无解[除0外逆序数之和奇偶一致]{左右移动不会改变逆序数之和，上下移动会导致逆序数+/-2}
        if ($src_puzzle->getInverseNumber() % 2 != $dst_puzzle->getInverseNumber() % 2) {
            // 无解!
            return;
        }
        $this->src_puzzle = $src_puzzle;
        $this->dst_puzzle = $dst_puzzle;
    }

    private function directionMap(): array
    {
        return [
            'top' => '↑',
            'right' => '→',
            'bottom' => '↓',
            'left' => '←',
        ];
    }

    private function getMoveDirection(array $roads): string
    {
        $move_direction = '';
        for ($i = 0; $i < count($roads) - 1; $i++) {
            $move_direction .= $this->directionMap()[$roads[$i]->getMoveDirection($roads[$i + 1])] ?? '+';
        }
        return $move_direction;
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

    private function printRoads(array $roads, $plain = true)
    {
        foreach ($roads as $index => $puzzle) {
            if ($puzzle instanceof Puzzle) {
                echo $puzzle->getPuzzleInfo($plain) . PHP_EOL;
                if ($index < count($roads) - 1) {
                    echo ($index + 1) . "=>" . PHP_EOL;
                }
            }
        }
    }

    public function bfs()
    {
        echo "*********** start Puzzle BFS **********" . PHP_EOL;
        $is_find = false;
        $open_list = [$this->src_puzzle];
        $close_list = [];
        $search_time = 0;
        while ($open_list) {
            $current_puzzle = array_shift($open_list);
            if (!$current_puzzle instanceof Puzzle) {
                // 这一步是IDEA类型提示、跳转
                continue;
            }
            $close_list[] = $current_puzzle;
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
                    $this->inPuzzles($child_puzzle, $open_list)
                    || $this->inPuzzles($child_puzzle, $close_list)
                ) {
                    continue;
                }
                $child_puzzle->level = $current_puzzle->level + 1;
                $child_puzzle->parent = $current_puzzle;
                $current_puzzle->setChild($child_puzzle);
                $open_list[] = $child_puzzle;
            }
            $search_time++;
            echo "搜索第{$search_time}次，层级{$current_puzzle->level}" . PHP_EOL;
        }
        if (!$is_find) {
            echo "移动不到目标位置" . PHP_EOL;
        } else {
            $roads = $this->dst_puzzle->getRoads();
            echo "需要移动" . (count($roads) - 1) . "步" . PHP_EOL;
            $this->printRoads($roads, false);
            echo "移动的路径为：" . $this->getMoveDirection($roads) . PHP_EOL;
        }
        echo "*********** stop Puzzle BFS **********" . PHP_EOL;
    }

    public function bidirectionalBfs()
    {
        echo "*********** start Puzzle Bidirectional BFS **********" . PHP_EOL;
        $is_find = false;
        $head_open_list = [$this->src_puzzle];
        $head_close_list = [];
        $tail_open_list = [$this->dst_puzzle];
        $tail_close_list = [];
        $this->dst_puzzle->parent = null;
        $this->dst_puzzle->level = 0;
        $this->dst_puzzle->clearChildren();

        $search_time = 0;
        while ($head_open_list && $tail_open_list) {
            $search_time++;
            $head_puzzle = array_shift($head_open_list);
            if (!$head_puzzle instanceof Puzzle) {
                // 这一步是IDEA类型提示、跳转
                continue;
            }
            $head_close_list[] = $head_puzzle;
            $pos = $head_puzzle->getZeroPos();
            if ($pos[0] == -1 || $pos[1] == -1) {
                break;
            }
            $moves = ['left' => 1, 'right' => 1, 'top' => 1, 'bottom' => 1];
            if ($pos[0] == 0) {
                unset($moves['top']);
            } elseif ($pos[0] == $head_puzzle->getLength() - 1) {
                unset($moves['bottom']);
            }
            if ($pos[1] == 0) {
                unset($moves['left']);
            } elseif ($pos[1] == $head_puzzle->getLength() - 1) {
                unset($moves['right']);
            }
            foreach ($moves as $direction => $placeholder) {
                $child_puzzle = $head_puzzle->moving($direction, $pos);
                if (
                    $this->inPuzzles($child_puzzle, $head_open_list)
                    || $this->inPuzzles($child_puzzle, $head_close_list)
                ) {
                    continue;
                }
                $child_puzzle->level = $head_puzzle->level + 1;
                $child_puzzle->parent = $head_puzzle;
                $head_puzzle->setChild($child_puzzle);
                $head_open_list[] = $child_puzzle;
            }

            $tail_puzzle = array_shift($tail_open_list);
            if (!$tail_puzzle instanceof Puzzle) {
                // 这一步是IDEA类型提示、跳转
                continue;
            }
            $tail_close_list[] = $tail_puzzle;
            $pos = $tail_puzzle->getZeroPos();
            if ($pos[0] == -1 || $pos[1] == -1) {
                break;
            }
            $moves = ['left' => 1, 'right' => 1, 'top' => 1, 'bottom' => 1];
            if ($pos[0] == 0) {
                unset($moves['top']);
            } elseif ($pos[0] == $tail_puzzle->getLength() - 1) {
                unset($moves['bottom']);
            }
            if ($pos[1] == 0) {
                unset($moves['left']);
            } elseif ($pos[1] == $tail_puzzle->getLength() - 1) {
                unset($moves['right']);
            }
            foreach ($moves as $direction => $placeholder) {
                $child_puzzle = $tail_puzzle->moving($direction, $pos);
                if (
                    $this->inPuzzles($child_puzzle, $tail_open_list)
                    || $this->inPuzzles($child_puzzle, $tail_close_list)
                ) {
                    continue;
                }
                $child_puzzle->level = $tail_puzzle->level + 1;
                $child_puzzle->parent = $tail_puzzle;
                $tail_puzzle->setChild($child_puzzle);
                $tail_open_list[] = $child_puzzle;
            }
            echo "搜索第{$search_time}次，头搜索层级{$head_puzzle->level}，尾搜索层级{$tail_puzzle->level}" . PHP_EOL;

            // 判断相遇,open_list包含重复项
            foreach ($head_open_list as $puzzle) {
                if ($tail_puzzle->equal($puzzle)) {
                    // 队尾正在处理的已经被队首发现了
                    $is_find = true;
                    $this->dst_puzzle = $puzzle;
                    $tail_puzzle = $tail_puzzle->parent;
                    while ($tail_puzzle) {
                        $this->dst_puzzle = $tail_puzzle;
                        $tail_parent_puzzle = $tail_puzzle->parent;
                        $tail_puzzle->parent = $puzzle;
                        $puzzle = $tail_puzzle;
                        $tail_puzzle = $tail_parent_puzzle;
                    }
                    break;
                }
                foreach ($tail_close_list as $tail_close_puzzle) {
                    if ($tail_close_puzzle->equal($puzzle)) {
                        $this->dst_puzzle = $puzzle;
                        $tail_close_puzzle = $tail_close_puzzle->parent;
                        while ($tail_close_puzzle) {
                            $this->dst_puzzle = $tail_close_puzzle;
                            $tail_parent_puzzle = $tail_close_puzzle->parent;
                            $tail_close_puzzle->parent = $puzzle;
                            $puzzle = $tail_close_puzzle;
                            $tail_close_puzzle = $tail_parent_puzzle;
                        }
                        $is_find = true;
                        break;
                    }
                }
                if ($is_find) {
                    break;
                }
            }
            if ($is_find) {
                break;
            }
        }
        if (!$is_find) {
            echo "移动不到目标位置" . PHP_EOL;
        } else {
            $roads = $this->dst_puzzle->getRoads();
            echo "需要移动" . (count($roads) - 1) . "步" . PHP_EOL;
            $this->printRoads($roads, false);
            echo "移动的路径为：" . $this->getMoveDirection($roads) . PHP_EOL;
        }
        echo "*********** stop Puzzle Bidirectional BFS **********" . PHP_EOL;
    }
}
