<?php

namespace common\point;

class Puzzle implements Node
{
    private array $map;
    private array $children;
    public int $level = 0;
    public ?Puzzle $parent = null;

    public function __construct(array $map)
    {
        $length = sqrt(count($map) + 1);
        if (is_int($length)) {
            throw new \Exception("初始化失败");
        }
        $length = intval($length);
        for ($x = 0; $x < $length; $x++) {
            $this->map[$x] = [];
            for ($y = 0; $y < $length; $y++) {
                $this->map[$x][$y] = (new Point($x, $y))->setValue($map[$x * $length + $y]);
            }
        }
    }

    private function getPrimitiveMap(): array
    {
        $primitive_map = [];
        foreach ($this->map as $x => $row) {
            foreach ($row as $y => $point) {
                $primitive_map[$x * $this->getLength() + $y] = $point->getValue();
            }
        }
        return $primitive_map;
    }

    public function getPuzzleInfo($plain = true, $indent = ''): string
    {
        if ($plain) {
            return "{$indent}[" . implode(", ", array_map(function (array $row) {
                return implode(", ", array_map(function (Point $point) {
                    return $point->getValue();
                }, $row));
            }, $this->map)) . "]";
        }
        $ret = "{$indent}[\n";
        foreach ($this->map as $row) {
            $ret .= "{$indent}  [" . implode(", ", array_map(function (Point $point) {
                return $point->getValue();
            }, $row)) . "]\n";
        }
        $ret .= "{$indent}]";
        return $ret;
    }

    public function equal(Puzzle $puzzle): bool
    {
        if ($puzzle->getPuzzleInfo() == $this->getPuzzleInfo()) {
            return true;
        }
        return false;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function setChild(Puzzle $puzzle)
    {
        $this->children[] = $puzzle;
    }

    public function printChildren($plain = true)
    {
        echo "[\n";
        foreach ($this->children as $child) {
            echo $child->getPuzzleInfo($plain, "  ") . PHP_EOL;
        }
        echo "]\n";
    }

    public function getZeroPos(): array
    {
        foreach ($this->map as $x => $row) {
            foreach ($row as $y => $point) {
                if ($point->getValue() == 0) {
                    return [$x, $y];
                }
            }
        }
        return  [-1, -1];
    }

    public function getLength(): int
    {
        return count($this->map);
    }

    public function moving($direction, $pos): Puzzle
    {
        switch ($direction) {
            case 'top':
                $dst_pos = [$pos[0] - 1, $pos[1]];
                break;
            case 'right':
                $dst_pos = [$pos[0], $pos[1] + 1];
                break;
            case 'bottom':
                $dst_pos = [$pos[0] + 1, $pos[1]];
                break;
            default:
                $dst_pos = [$pos[0], $pos[1] - 1];
                break;
        }
        $map = $this->getPrimitiveMap();
        $tmp_value = $map[$pos[0] * $this->getLength() + $pos[1]];
        $map[$pos[0] * $this->getLength() + $pos[1]] = $map[$dst_pos[0] * $this->getLength() + $dst_pos[1]];
        $map[$dst_pos[0] * $this->getLength() + $dst_pos[1]] = $tmp_value;
        return new Puzzle($map);
    }

    public function getRoads(): array
    {
        $puzzle = $this;
        $roads = [];
        while ($puzzle) {
            array_unshift($roads, $puzzle);
            $puzzle = $puzzle->parent;
        }
        return $roads;
    }
}
