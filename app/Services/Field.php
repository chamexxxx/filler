<?php

namespace App\Services;

class Field
{
    private static $colors = ['blue', 'green', 'cyan', 'red', 'magenta', 'yellow', 'white'];

    public static function generate(int $width, int $height, callable $callback): array
    {
        $cells = [];

        for ($row = 1; $row <= $height; $row++) {
            $rowWidth = $row % 2 !== 0 ? $width : $width - 1;

            for ($column = 1; $column <= $rowWidth; $column++) {
                $cell = call_user_func($callback, self::getRandomColor());
                array_push($cells, $cell);
            }
        }

        return $cells;
    }

    public static function getRandomColor()
    {
        return self::$colors[array_rand(self::$colors)];
    }
}
