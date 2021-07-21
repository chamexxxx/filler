<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\Filler\Field;

class FieldTest extends TestCase
{
    private $field;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->field = new Field(7, 7);
    }

    public function testFieldCount()
    {
        $this->assertSame(23, Field::count(5, 5));
        $this->assertSame(9752, Field::count(99, 99));
    }

    public function testNullableCells()
    {
        $this->assertSame(null, $this->field->getCell(8, 8));
        $this->assertSame(null, $this->field->getCell(-1, -1));
    }

    public function testAdjacentCells()
    {
        $cell = $this->field->getCell(3, 3);

        $this->assertSame(['row' => 3, 'column' => 4], $cell->getRightCell()->getPosition());
        $this->assertSame(['row' => 3, 'column' => 2], $cell->getLeftCell()->getPosition());
        $this->assertSame(['row' => 2, 'column' => 2], $cell->getTopLeftCell()->getPosition());
        $this->assertSame(['row' => 2, 'column' => 3], $cell->getTopRightCell()->getPosition());
        $this->assertSame(['row' => 4, 'column' => 2], $cell->getBottomLeftCell()->getPosition());
        $this->assertSame(['row' => 4, 'column' => 3], $cell->getBottomRightCell()->getPosition());
        $this->assertSame(['row' => 1, 'column' => 3], $cell->getTopCell()->getPosition());
        $this->assertSame(['row' => 5, 'column' => 3], $cell->getBottomCell()->getPosition());
    }
}
