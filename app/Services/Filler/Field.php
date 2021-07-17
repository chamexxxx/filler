<?php

namespace App\Services\Filler;

class Field
{
    private $width;

    private $height;

    private $cells = [];

    private $startingPositions = [];

    private $currentColors = [];

    private $currentPlayerNumber = 1;

    private const COLORS = ['blue', 'green', 'cyan', 'red', 'magenta', 'yellow', 'white'];

    /**
     * The main directions of the cells
     * Opposites are indicated through one element
     */
    private const DIRECTIONS = ['TopLeft', 'TopRight', 'BottomRight', 'BottomLeft'];

    /**
     * Getting a random color
     *
     * @return string
     */
    public static function getRandomColor(): string
    {
        return self::COLORS[array_rand(self::COLORS)];
    }

    /**
     * Getting the number of cells using the width and height of the field
     *
     * @param integer $width
     * @param integer $height
     * @return integer
     */
    public static function count(int $width, int $height): int
    {
        $even = floor($height / 2);
        $uneven = $height - $even;

        return $even * ($width - 1) + $uneven * $width;
    }

    /**
     * Field constructor
     *
     * @param integer $width
     * @param integer $height
     * @param array|null $cells
     * @param array|null $currentColors
     */
    public function __construct(int $width, int $height, array $cells = null, array $currentColors = null)
    {
        $this->width = $width;
        $this->height = $height;
        $cells ? $this->fill($cells) : $this->generate();
        $this->initializeStartingPositions();
        $currentColors ?
            $this->setCurrentColors($currentColors[0], $currentColors[1]) :
            $this->initializeCurrentColors();
    }

    /**
     * Iterate over a cells, executing a function for each element
     *
     * @param callable $callback
     * @return void
     */
    public function each(callable $callback): void
    {
        foreach ($this->cells as $row) {
            foreach ($row as $cell) {
                call_user_func($callback, $cell);
            }
        }
    }

    /**
     * Convert all cells to a new array of elements by executing a function for each element
     *
     * @param callable $callback
     * @return array
     */
    public function map(callable $callback): array
    {
        $cells = [];

        $this->each(function ($cell) use (&$cells, $callback) {
            $cells[] = call_user_func($callback, $cell);
        });

        return $cells;
    }

    /**
     * Convert all cells to array of elements
     *
     * @param array|null $renamedProperties Properties for renaming
     * @param array|null $additionalProperties Properties to add
     * @return array
     */
    public function toArray(array $renamedProperties = null, array $additionalProperties = null): array
    {
        return $this->map(function ($cell) use ($renamedProperties, $additionalProperties) {
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

    /**
     * Get cell
     *
     * @param integer $row
     * @param integer $column
     * @return Cell|null
     */
    public function getCell(int $row, int $column): ?Cell
    {
        return $this->validateCell($row, $column)
            ? $this->cells[$row][$column] : null;
    }

    /**
     * Get start cell
     *
     * @param integer $playerNumber
     * @return Cell|null
     */
    public function getStartingCell(int $playerNumber): ?Cell
    {
        $startingPosition = $this->startingPositions[$playerNumber - 1];
        return $this->getCell($startingPosition['row'], $startingPosition['column']);
    }

    /**
     * Get the current color
     *
     * @param integer $playerNumber
     * @return string
     */
    public function getCurrentColor(int $playerNumber): string
    {
        return $this->currentColors[$playerNumber - 1];
    }

    /**
     * Make a move in the game
     *
     * @param string $color
     * @param integer $playerNumber
     * @return boolean
     */
    public function step(string $color, int $playerNumber): bool
    {
        if (!$this->isStepAllowed($color)) {
            return false;
        }

        $this->currentPlayerNumber = $playerNumber;

        $startingCell = $this->getStartingCell($playerNumber);

        $cluster = $this->getCluster($startingCell, function ($cell) use ($color, $playerNumber) {
            if ($cell->color === $color) {
                $cell->playerNumber = $playerNumber;
            }
        });

        foreach ($cluster as $cell) {
            $cell->color = $color;
            $cell->playerNumber = $playerNumber;
        }

        return true;
    }

    /**
     * Print all cells
     *
     * @return void
     */
    public function print()
    {
        $this->each(function ($cell) {
            echo $cell->color . " " . $cell->row . " " . $cell->column . "\n";
        });
    }

    /**
     * Get a group of cells merged with one color relative to a given cell
     *
     * @param Cell $cell Cell relative to which the search will be conducted
     * @param callable|null $extremeCallback Called when the outermost cells do not match the color
     * @return array
     */
    private function getCluster(Cell $cell, callable $extremeCallback = null): array
    {
        $cells = [$cell];

        $this->getAllNeighbors($cell, function ($cell) use (&$cells) {
            $cells[] = $cell;
        }, $extremeCallback);

        return $cells;
    }

    /**
     * Recursive function of getting all neighbors of cells by color via callback functions
     *
     * @param Cell $cell Cell relative to which the search will be conducted
     * @param callable $callback Called when a neighbor is found
     * @param callable|null $extremeCallback Called when the outermost cells do not match the color
     * @param string|null $color Color for which to count neighbors
     * @param string|null $direction Direction to search for next cell
     * @param array $cells Additional array to exclude repetitions
     * @return void
     */
    private function getAllNeighbors(
        Cell $cell,
        callable $callback,
        callable $extremeCallback = null,
        string $color = null,
        string $direction = null,
        array &$cells = []
    ): void {
        if (!$color) {
            $color = $cell->color;
        }

        if (!$direction) {
            $this->getAllNeighbors($cell, $callback, $extremeCallback, $color, 'TopLeft', $cells);
            $this->getAllNeighbors($cell, $callback, $extremeCallback, $color, 'TopRight', $cells);
            $this->getAllNeighbors($cell, $callback, $extremeCallback, $color, 'BottomLeft', $cells);
            $this->getAllNeighbors($cell, $callback, $extremeCallback, $color, 'BottomRight', $cells);

            return;
        }

        $nextCell = $this->getNeighbor($cell, $direction);

        if ($nextCell && $nextCell->color === $color && !$this->cellExistsInArray($nextCell, $cells)) {

            call_user_func($callback, $nextCell);

            $cells[] = $nextCell;

            $directions = $this->getValidDirectionsForPlayer($direction, $this->currentPlayerNumber);

            foreach ($directions as $direction) {
                $this->getAllNeighbors($nextCell, $callback, $extremeCallback, $color, $direction, $cells);
            }
        } else if ($nextCell && $extremeCallback) {
            call_user_func($extremeCallback, $nextCell);
        }
    }

    /**
     * Check if a cell exists in an array
     *
     * @param Cell $cell
     * @param array $cells
     * @return boolean
     */
    private function cellExistsInArray(Cell $cell, array $cells): bool
    {
        foreach ($cells as $cellFromArray) {
            if ($cell->row === $cellFromArray->row && $cell->column === $cellFromArray->column) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the neighbor of a cell in a given direction
     *
     * @param Cell $cell
     * @param string $direction
     * @return Cell|null
     */
    private function getNeighbor(Cell $cell, string $direction): ?Cell
    {
        return $cell->{'get' . $direction . 'Cell'}();
    }

    /**
     * Get all directions except opposite and reverse to the starting position
     *
     * @param string $direction
     * @param integer $playerNumber
     * @return array
     */
    private function getValidDirectionsForPlayer(string $direction, int $playerNumber): array
    {
        return array_diff(
            $this->getValidDirections($direction),
            [$playerNumber === 1 ? 'BottomLeft' : 'TopRight']
        );
    }

    /**
     * Get all directions except the opposite
     *
     * @param string $direction
     * @return array
     */
    private function getValidDirections(string $direction): array
    {
        $oppositeDirectionIndex = $this->getOppositeDirectionIndex($direction);

        return array_filter(self::DIRECTIONS, function ($key) use ($oppositeDirectionIndex) {
            return $key !== $oppositeDirectionIndex;
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Get the index of the opposite direction
     *
     * @param string $direction
     * @return integer
     */
    private function getOppositeDirectionIndex(string $direction): int
    {
        $key = array_search($direction, self::DIRECTIONS) + 2;
        $arrayLength = count(self::DIRECTIONS);

        return $this->getIndexWithExcess($key, $arrayLength);
    }

    /**
     * Get the normalized index
     *
     * @param integer $index
     * @param integer $arrayLength
     * @return integer
     */
    private function getIndexWithExcess(int $index, int $arrayLength): int
    {
        return $index > $arrayLength - 1 ? $index % $arrayLength : $index;
    }

    /**
     * Check if it is allowed to make a move in the game
     *
     * @param string $color
     * @return boolean
     */
    private function isStepAllowed(string $color): bool
    {
        return !in_array($color, $this->currentColors);
    }

    /**
     * Fill cells from an array
     *
     * @param array $cells
     * @return void
     */
    private function fill(array $cells): void
    {
        $this->iterate(function ($row, $column, $index) use ($cells) {
            $item = $cells[$index];

            $this->cells[$row][$column] = new Cell(
                $this,
                $row,
                $column,
                $item['color'],
                $item['playerId'],
                $item['id'],
            );
        });
    }

    /**
     * Generate cells by executing a function for each item
     *
     * @return void
     */
    private function generate()
    {
        $this->iterate(function ($row, $column) {
            $this->cells[$row][$column] = new Cell(
                $this,
                $row,
                $column,
                self::getRandomColor(),
                0,
                null,
            );
        });
    }

    /**
     * Iterate cells by executing a function for each element using the width and height of the field
     *
     * @param callable $callback
     * @return void
     */
    private function iterate(callable $callback)
    {
        for ($row = 1; $row <= $this->height; $row++) {
            $width = $row % 2 !== 0 ? $this->width : $this->width - 1;

            for ($column = 1; $column <= $width; $column++) {
                $index = self::count($this->width, $row - 1) + $column - 1;

                call_user_func($callback, $row, $column, $index);
            }
        }
    }

    /**
     * Initialize the starting positions of the cells
     *
     * @return void
     */
    private function initializeStartingPositions(): void
    {
        array_push(
            $this->startingPositions,
            [
                'row'    => $this->height,
                'column' => 1
            ],
            [
                'row'    => 1,
                'column' => $this->width
            ]
        );
    }

    /**
     * Set current colors
     *
     * @param string $firstColor
     * @param string $secondColor
     * @return void
     */
    private function setCurrentColors(string $firstColor, string $secondColor): void
    {
        array_push($this->currentColors, $firstColor, $secondColor);
    }

    /**
     * Initialize current colors with start cells
     *
     * @return void
     */
    private function initializeCurrentColors(): void
    {
        $this->setCurrentColors(
            $this->getStartingCell(1)->color,
            $this->getStartingCell(2)->color,
        );
    }

    /**
     * Cell validation
     *
     * @param integer $row
     * @param integer $column
     * @return boolean
     */
    private function validateCell(int $row, int $column): bool
    {
        return ($row <= $this->height && $row > 0
            && $column > 0
            && (($row % 2 !== 0 && $column <= $this->width)
                || ($row % 2 === 0 && $column <= $this->width - 1)));
    }
}
