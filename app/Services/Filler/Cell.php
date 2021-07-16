<?php

namespace App\Services\Filler;

class Cell
{
    public $row;

    public $column;

    public $color;

    public $playerNumber;

    public $id;

    private $field;

    public function __construct(int $row, int $column, string $color, int $playerNumber, int $id = null, Field &$field)
    {
        $this->row = $row;
        $this->column = $column;
        $this->color = $color;
        $this->playerNumber = $playerNumber;
        $this->id = $id;
        $this->field = $field;
    }

    public function getLeftCell(): ?Cell
    {
        return $this->field->getCell($this->row, $this->column - 1);
    }

    public function getRightCell(): ?Cell
    {
        return $this->field->getCell($this->row, $this->column + 1);
    }

    public function getTopCell(): ?Cell
    {
        return $this->field->getCell($this->row - 2, $this->column);
    }

    public function getBottomCell(): ?Cell
    {
        return $this->field->getCell($this->row + 2, $this->column);
    }

    public function getTopLeftCell(): ?Cell
    {
        $column = $this->row % 2 === 0 ? $this->column : $this->column - 1;
        return $this->field->getCell($this->row - 1, $column);
    }

    public function getBottomLeftCell(): ?Cell
    {
        $column = $this->row % 2 === 0 ? $this->column : $this->column - 1;
        return $this->field->getCell($this->row + 1, $column);
    }

    public function getTopRightCell(): ?Cell
    {
        $column = $this->row % 2 === 0 ? $this->column + 1 : $this->column;
        return $this->field->getCell($this->row - 1, $column);
    }

    public function getBottomRightCell(): ?Cell
    {
        $column = $this->row % 2 === 0 ? $this->column + 1 : $this->column;
        return $this->field->getCell($this->row + 1, $column);
    }

    public function getRelativeTopCell(): ?Cell
    {
        return $this->field->getCell($this->row - 1, $this->column);
    }

    public function getRelativeBottomCell(): ?Cell
    {
        return $this->field->getCell($this->row + 1, $this->column);
    }
}
