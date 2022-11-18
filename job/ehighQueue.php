<?php

class Solution {
    /**
     * @param Integer $n
     * @return String[][]
     */
    public function solveNQueens($n)
    {
        $columns = [];
        $diagonal1 = [];
        $diagonal2 = [];
        $queues = [];
        $one_row = [];
        $solutions = [];
        for ($i = 0; $i < $n; $i++) {
            $queues[$i] = -1;
            $one_row[$i] = '.';
        }

        $this->solve(0, $n, $columns, $diagonal1, $diagonal2, $queues, $one_row, $solutions);
        return $solutions;
    }

    private function solve($row, $n, $columns, $diagonal1, $diagonal2, $queues, $one_row, &$solutions)
    {
        if ($row == $n) {
            $board = [];
            for ($i = 0; $i < $n; $i++) {
                $one_row[$queues[$i]] = 'Q';
                $board[] = implode("", $one_row);
                $one_row[$queues[$i]] = '.';
            }
            $solutions[] = $board;
        } else {
            for ($column = 0; $column < $n; $column++) {
                if (
                    isset($columns[$column])
                    || isset($diagonal1[$column - $row])
                    || isset($diagonal2[$column + $row])
                ) {
                    continue;
                }
                $queues[$row] = $column;
                $columns[$column] = 1;
                $diagonal1[$column - $row] = 1;
                $diagonal2[$column + $row] = 1;
                $this->solve($row + 1, $n, $columns, $diagonal1, $diagonal2, $queues, $one_row, $solutions);
                unset($columns[$column], $diagonal1[$column - $row], $diagonal2[$column + $row]);
                $queues[$row] = -1;
            }
        }
    }
}

echo json_encode((new Solution())->solveNQueens(4)) . PHP_EOL;
