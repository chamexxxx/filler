<?php

namespace App\Services\Filler;

/**
 * Game class that allows you to make moves and identify winners.
 * @package App\Services\Filler
 */
class Filler
{
    public $field;

    private $currentColors = [];

    /**
     * Filler constructor.
     *
     * @param int $fieldWidth
     * @param int $fieldHeight
     * @param array|null $cells
     */
    public function __construct(int $fieldWidth, int $fieldHeight, array $cells = null)
    {
        $this->field = new Field($fieldWidth, $fieldHeight);
        $cells ? $this->field->fill($cells) : $this->field->generate();

        $this->initializeCurrentColors();
    }

    /**
     * Make a move in the game.
     * @param string $color
     * @param integer $playerNumber
     * @return boolean
     */
    public function step(string $color, int $playerNumber): bool
    {
        if (!$this->isStepAllowed($color) || $this->isGameOver()) {
            return false;
        }

        $startingCell = $this->field->getStartingCell($playerNumber);

        $cluster = $this->field->getCluster($startingCell, function ($cell) use ($color, $playerNumber) {
            if ($cell->color === $color) {
                $cluster = $this->field->getCluster($cell);

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
     * Checks if the game is over.
     *
     * @return bool
     */
    public function isGameOver(): bool
    {
        return CellIterator::every($this->field->getCells(), function ($cell) {
            return $cell->playerNumber !== 0;
        });
    }

    /**
     * Get the winner of the game for the current state of the field.
     *
     * @return int
     */
    public function getWinner(): int
    {
        $numbers = $this->field->getNumberOfCells();

        return $numbers[1] > $numbers[2] ? 1 : 2;
    }

    /**
     * Get the current color.
     *
     * @param integer $playerNumber
     * @return string
     */
    public function getCurrentColor(int $playerNumber): string
    {
        return $this->currentColors[$playerNumber - 1];
    }

    /**
     * Check if it is allowed to make a move in the game.
     *
     * @param string $color
     * @return boolean
     */
    private function isStepAllowed(string $color): bool
    {
        return !in_array($color, $this->currentColors);
    }

    /**
     * Initialize current colors with start cells.
     *
     * @return void
     */
    private function initializeCurrentColors(): void
    {
        $this->setCurrentColors(
            $this->field->getStartingCell(1)->color,
            $this->field->getStartingCell(2)->color,
        );
    }

    /**
     * Set current colors.
     *
     * @param string $firstColor
     * @param string $secondColor
     * @return void
     */
    private function setCurrentColors(string $firstColor, string $secondColor): void
    {
        array_push($this->currentColors, $firstColor, $secondColor);
    }
}
