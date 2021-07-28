<?php

namespace App\Services\Filler;

class Field
{
    public const COLORS = ['blue', 'green', 'cyan', 'red', 'magenta', 'yellow', 'white'];

    /**
     * The main directions of the cells
     * Opposites are indicated through one element
     */
    private const DIRECTIONS = ['TopLeft', 'TopRight', 'BottomRight', 'BottomLeft'];

    private $width;

    private $height;

    private $cells = [];

    private $startingPositions = [];

    private $currentColors = [];

    /**
     * Getting a random color
     *
     * @param array|null $excludedColors
     * @return string
     */
    public static function getRandomColor(array $excludedColors = null): string
    {
        $colors = self::COLORS;

        if ($excludedColors) {
            $colors = array_diff($colors, $excludedColors);
        }

        return $colors[array_rand($colors)];
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
     * Iterate cells by executing a function for each element using the width and height of the field
     *
     * @param int $width
     * @param int $height
     * @param callable $callback
     * @return void
     */
    public static function iterate(int $width, int $height, callable $callback)
    {
        for ($row = 1; $row <= $height; $row++) {
            $rowWidth = $row % 2 !== 0 ? $width : $width - 1;

            for ($column = 1; $column <= $rowWidth; $column++) {
                $index = self::count($width, $row - 1) + $column - 1;

                call_user_func($callback, $row, $column, $index);
            }
        }
    }

    /**
     * Field constructor
     *
     * @param integer $width
     * @param integer $height
     * @param array|null $cells
     */
    public function __construct(int $width, int $height, array $cells = null)
    {
        $this->width = $width;
        $this->height = $height;

        if ($cells) {
            $this->fill($cells);
            $this->initializeStartingPositions();
        } else {
            $this->initializeCells();
            $this->initializeStartingPositions();
            $this->generateClusters();
        }

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
     * Checks if all cells satisfy the condition given in the passed function
     *
     * @param callable $callback
     * @return bool
     */
    public function every(callable $callback): bool
    {
        foreach ($this->cells as $row) {
            foreach ($row as $cell) {
                if (!call_user_func($callback, $cell)) {
                    return false;
                }
            }
        }

        return true;
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
     * Checks if the game is over
     *
     * @return bool
     */
    public function isGameOver(): bool
    {
        return $this->every(function ($cell) {
            return $cell->playerNumber !== 0;
        });
    }

    /**
     * Get the winner of the game for the current state of the field
     *
     * @return int
     */
    public function getWinner(): int
    {
        $numbers = $this->getNumberOfCells();

        return $numbers[1] > $numbers[2] ? 1 : 2;
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
        if (!$this->isStepAllowed($color) || $this->isGameOver()) {
            return false;
        }

        $startingCell = $this->getStartingCell($playerNumber);

        $cluster = $this->getCluster($startingCell, function ($cell) use ($color, $playerNumber) {
            if ($cell->color === $color) {
                $cluster = $this->getCluster($cell);

                foreach ($cluster as $cell) {
                    $cell->playerNumber = $playerNumber;
                }
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
     * Fill the field with clusters by color
     *
     * @return void
     */
    private function generateClusters(): void
    {
        $startingCell = $this->getStartingCell(1);
        $this->fillWithClusters($startingCell, self::getRandomColor());
    }

    /**
     * Recursive function of filling all clusters by color
     *
     * @param Cell $cell
     * @param string $color
     * @param string|null $direction
     * @param array $cells
     * @param int $number
     * @param int $limit
     * @return void
     */
    private function fillWithClusters(
        Cell $cell,
        string $color,
        string $direction = null,
        array &$cells = [],
        int $number = 1,
        int &$limit = 3
    ): void {
        if (!$direction) {
            foreach (self::DIRECTIONS as $direction) {
                $this->{__FUNCTION__}($cell, $color, $direction, $cells, $number, $limit);
            }
        }

        $exceeded = $number > $limit;

        if ($exceeded) {
            $number = 1;
            $limit = rand(5, 7);
        }

        if ($this->isStartingCell($cell, 2)) {
            $color = $exceeded ?
                self::getRandomColor([$this->getStartingCell(1)->color, $color]) :
                self::getRandomColor([$this->getStartingCell(1)->color]);
        } else if ($exceeded) {
            $color = self::getRandomColor([$color]);
        }

        $cell->color = $color;

        $cells[] = $cell;

        $nextCell = $this->getNeighbor($cell, $direction);

        if ($nextCell && !$this->cellExistsInArray($nextCell, $cells)) {
            $directions = $this->getValidDirections($direction);
            shuffle($directions);

            foreach ($directions as $direction) {
                $number++;
                $this->{__FUNCTION__}($nextCell, $color, $direction, $cells, $number, $limit);
            }
        }
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
            foreach (self::DIRECTIONS as $direction) {
                $this->{__FUNCTION__}($cell, $callback, $extremeCallback, $color, $direction, $cells);
            }

            return;
        }

        $nextCell = $this->getNeighbor($cell, $direction);

        if ($nextCell && $nextCell->color === $color && !$this->cellExistsInArray($nextCell, $cells)) {

            call_user_func($callback, $nextCell);

            $cells[] = $nextCell;

            $directions = $this->getValidDirections($direction);

            foreach ($directions as $direction) {
                $this->{__FUNCTION__}($nextCell, $callback, $extremeCallback, $color, $direction, $cells);
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
     * Get the number of cells for each player
     *
     * @return array
     */
    private function getNumberOfCells(): array
    {
        $numberOfFreeSlots = 0;
        $numberOfSlotsForTheFirstPlayer = 0;
        $numberOfSlotsForTheSecondPlayer = 0;

        $this->each(function ($cell) use (&$numberOfFreeSlots, &$numberOfSlotsForTheFirstPlayer, &$numberOfSlotsForTheSecondPlayer) {
            if ($cell->playerNumber === 1) {
                $numberOfSlotsForTheFirstPlayer++;
            } else if ($cell->playerNumber === 2) {
                $numberOfSlotsForTheSecondPlayer++;
            } else {
                $numberOfFreeSlots++;
            }
        });

        return [$numberOfFreeSlots, $numberOfSlotsForTheFirstPlayer, $numberOfSlotsForTheSecondPlayer];
    }

    /**
     * Fill cells from an array
     *
     * @param array $cells
     * @return void
     */
    private function fill(array $cells): void
    {
        self::iterate($this->width, $this->height, function ($row, $column, $index) use ($cells) {
            $item = $cells[$index];

            $this->cells[$row][$column] = new Cell(
                $this,
                $row,
                $column,
                $item['playerId'],
                $item['color'],
                $item['id'],
            );
        });
    }

    private function initializeCells()
    {
        self::iterate($this->width, $this->height, function ($row, $column) {
            $this->cells[$row][$column] = new Cell($this, $row, $column, 0);
        });
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
     * Checks if the cell is the starting cell for the player
     *
     * @param Cell $cell
     * @param int $playerNumber
     * @return bool
     */
    private function isStartingCell(Cell $cell, int $playerNumber): bool
    {
        $startingCell = $this->getStartingCell($playerNumber);
        return $cell->row === $startingCell->row && $cell->column && $startingCell->column;
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
