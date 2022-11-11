<?php

namespace common;

class Color
{
    private int $r;
    private int $g;
    private int $b;
    private static array $colors = [];
    private static ?Color $instance = null;

    public function __construct($r = 0, $g = 0, $b = 0)
    {
        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
        self::setInstance($this);
    }

    public static function setInstance($instance)
    {
        self::$instance = $instance;
    }

    public static function getInstance(): Color
    {
        if (is_null(self::$instance)) {
            self::setInstance(new static());
        }
        return self::$instance;
    }

    public function __toString()
    {
        return "{$this->r};{$this->g};{$this->b}";
    }

    public static function initColor()
    {
        self::$colors = [
            'red' => '255;0;0',
            'yellow' => '255;255;0',
            'green' => '0;255;0',
            'cyan' => '0;255;255',
            'blue' => '0;0;255',
            'magenta' => '255;0;255',
            'maroon' => '128;0;0',
            'olive' => '128;128;0',
            'dark green' => '0;128;0',
            'blue green' => '0;128;128',
            'navy blue' => '0;0;128',
            'purple' => '128;0;128',
            'white' => '255;255;255',
            'silver' => '192;192;192',
            'grey' => '128;128;128',
            'black' => '0;0;0',
        ];
    }

    public static function setColor($name, $r, $g, $b): Color
    {
        self::$colors[$name] = "$r;$g;$b";
        return self::$instance;
    }

    public static function getColor($name = '')
    {
        if (!self::$colors) {
            self::initColor();
        }
        return self::$colors[$name] ?? null;
    }
}
