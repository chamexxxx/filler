<?php

namespace App\Services;

class Field
{
    private static $colors = ['blue', 'green', 'cyan', 'red', 'magenta', 'yellow', 'white'];

    public static function generate(int $width, int $height, callable $callback): array
    {
        $count = self::count($width, $height);
        $cells = [];

        for ($index = 0; $index < $count; $index++) {
            $cells[] = call_user_func($callback, self::getRandomColor());
        }

        return $cells;
    }

    public static function count(int $width, int $height): int
    {
        $even = floor($height / 2);
        $uneven = $height - $even;

        return $even * ($width - 1) + $uneven * $width;
    }

    public static function getRandomColor()
    {
        return self::$colors[array_rand(self::$colors)];
    }
}
