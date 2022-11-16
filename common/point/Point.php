<?php

namespace common\point;

class Point
{
    public int $x;
    public int $y;
    public ?Point $parent = null;
    private array $adjoins = [];
    private string $value = '0';
    private int $price = 0;
    private int $block = 0;
    public int $cost = 0;
    public int $distance = 0;

    public function __construct($x = 0, $y = 0)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function equal(Point $point): bool
    {
        if ($this->x == $point->x && $this->y == $point->y) {
            return true;
        }
        return false;
    }

    public function getPointInfo(): string
    {
        return "point: ({$this->x},{$this->y}), block: {$this->block}, price: {$this->price}, value: {$this->value}";
    }

    public function setBlock($block = 0): Point
    {
        // 这里做的是0通畅、1阻塞，没有做移动损耗
        $this->block = $block;
        return $this;
    }

    public function getBlock(): int
    {
        return $this->block;
    }

    public function setValue($value): Point
    {
        $this->value = $value;
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setPrice($price): Point
    {
        $this->price = $price;
        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setAdjoin(Point $point): Point
    {
        if (!$point->getBlock()) {
            $this->adjoins[] = $point;
        }
        return $this;
    }

    public function isAdjoin(Point $point): bool
    {
        return in_array($point, $this->adjoins);
    }

    public function getAdjoinPoints(): array
    {
        if ($this->block) {
            $this->adjoins = [];
            return [];
        }
        $adjoins = [];
        foreach ($this->adjoins as $adjoin) {
            if (!$adjoin->getBlock()) {
                $adjoins[] = $adjoin;
            }
        }
        $this->adjoins = $adjoins;
        return $this->adjoins;
    }

    public function __toString()
    {
        return $this->getPointInfo();
    }
}
