<?php

namespace App\Services\Filler;

/**
 * Game field cell class
 * @package App\Services\Filler
 */
class Cell
{
    public $row;

    public $column;

    public $color;

    public $playerNumber;

    public $id;

    private $field;

    /**
     * Cell constructor.
     *
     * @param Field $field
     * @param int $row
     * @param int $column
     * @param int $playerNumber
     * @param string|null $color
     * @param int|null $id
     */
    public function __construct(Field &$field, int $row, int $column, int $playerNumber, string $color = null, int $id = null)
    {
        $this->field = $field;
        $this->row = $row;
        $this->column = $column;
        $this->playerNumber = $playerNumber;
        $this->color = $color;
        $this->id = $id;
    }

    /**
     * Returns the position of the cell in the field
     *
     * Return value example:
     * ```php
     * [
     *  'row'    => 1,
     *  'column' => 1,
     * ]
     * ```
     *
     * @return array
     */
    public function getPosition(): array
    {
        return [
            'row'    => $this->row,
            'column' => $this->column
        ];
    }

    /**
     * @return Cell|null
     */
    public function getLeftCell(): ?Cell
    {
        return $this->field->getCell($this->row, $this->column - 1);
    }

    /**
     * @return Cell|null
     */
    public function getRightCell(): ?Cell
    {
        return $this->field->getCell($this->row, $this->column + 1);
    }

    /**
     * @return Cell|null
     */
    public function getTopCell(): ?Cell
    {
        return $this->field->getCell($this->row - 2, $this->column);
    }

    /**
     * @return Cell|null
     */
    public function getBottomCell(): ?Cell
    {
        return $this->field->getCell($this->row + 2, $this->column);
    }

    /**
     * @return Cell|null
     */
    public function getTopLeftCell(): ?Cell
    {
        $column = $this->row % 2 === 0 ? $this->column : $this->column - 1;
        return $this->field->getCell($this->row - 1, $column);
    }

    /**
     * @return Cell|null
     */
    public function getBottomLeftCell(): ?Cell
    {
        $column = $this->row % 2 === 0 ? $this->column : $this->column - 1;
        return $this->field->getCell($this->row + 1, $column);
    }

    /**
     * @return Cell|null
     */
    public function getTopRightCell(): ?Cell
    {
        $column = $this->row % 2 === 0 ? $this->column + 1 : $this->column;
        return $this->field->getCell($this->row - 1, $column);
    }

    /**
     * @return Cell|null
     */
    public function getBottomRightCell(): ?Cell
    {
        $column = $this->row % 2 === 0 ? $this->column + 1 : $this->column;
        return $this->field->getCell($this->row + 1, $column);
    }
}
