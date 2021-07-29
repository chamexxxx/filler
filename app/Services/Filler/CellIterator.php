<?php

namespace App\Services\Filler;

class CellIterator {
    /**
     * Iterate over a cells, executing a function for each element.
     *
     * @param array $cells
     * @param callable $callback
     * @return void
     */
    public static function each(array $cells, callable $callback): void
    {
        foreach ($cells as $row) {
            foreach ($row as $cell) {
                call_user_func($callback, $cell);
            }
        }
    }

    /**
     * Checks if all cells satisfy the condition given in the passed function.
     *
     * @param array $cells
     * @param callable $callback
     * @return bool
     */
    public static function every(array $cells, callable $callback): bool
    {
        foreach ($cells as $row) {
            foreach ($row as $cell) {
                if (!call_user_func($callback, $cell)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Convert all cells to a new array of elements by executing a function for each element.
     *
     * @param array $cells
     * @param callable $callback
     * @return array
     */
    public static function map(array $cells, callable $callback): array
    {
        $tempCells = [];

        self::each($cells, function ($cell) use (&$tempCells, $callback) {
            $tempCells[] = call_user_func($callback, $cell);
        });

        return $tempCells;
    }

    /**
     * Convert all cells to array of elements.
     *
     * @param array $cells
     * @param array|null $renamedProperties Properties for renaming
     * @param array|null $additionalProperties Properties to add
     * @return array
     */
    public static function toArray(array $cells, array $renamedProperties = null, array $additionalProperties = null): array
    {
        return self::map($cells, function ($cell) use ($renamedProperties, $additionalProperties) {
            $item = [
                'color' => $cell->color,
                'player_id' => $cell->playerNumber,
            ];

            foreach ($renamedProperties as $oldKey => $newKey) {
                if (array_key_exists($oldKey, $item)) {
                    $item[$newKey] = $item[$oldKey];
                    unset($item[$oldKey]);
                }
            }

            if ($cell->id !== null) {
                $item['id'] = $cell->id;
            }

            if ($additionalProperties) {
                $item = array_merge($item, $additionalProperties);
            }

            return $item;
        });
    }
}
