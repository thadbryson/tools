<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\Str;

use const M_PI;
use const PHP_INT_MAX;
use const PHP_INT_MIN;
use Tool\Support\Str;
use UnitTester;

class BooleanTraitsTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public function testIsEmpty(): void
    {
        $this->assertTrue(Str::make('')->isEmpty());
        $this->assertFalse(Str::make(' ')->isEmpty());
        $this->assertFalse(Str::make('a string here')->isEmpty());
    }

    public function testIsNotEmpty(): void
    {
        $this->assertFalse(Str::make('')->isNotEmpty());
        $this->assertTrue(Str::make(' ')->isNotEmpty());
        $this->assertTrue(Str::make('a string here')->isNotEmpty());
    }

    public function testHasSubstr(): void
    {
        $str = Str::make('abcdef');

        $this->assertTrue($str->hasSubstr('a'));
        $this->assertFalse($str->hasSubstr('A'));

        $this->assertTrue($str->hasSubstr('a', false));
        $this->assertTrue($str->hasSubstr('A', false));

        $this->assertFalse($str->hasSubstr(' '));
        $this->assertFalse($str->hasSubstr('h'));
    }

    public function testIsTimezone(): void
    {
        $this->assertTrue(Str::make('America/New_York')->isTimezone());
        $this->assertTrue(Str::make('America/New_York ')->isTimezone());
        $this->assertTrue(Str::make('america/New_York')->isTimezone());

        $this->assertFalse(Str::make('nope')->isTimezone());
        $this->assertFalse(Str::make('America/Davie')->isTimezone());
    }

    public function testIsNumeric(): void
    {
        $this->assertTrue(Str::make('0')->isNumeric());
        $this->assertTrue(Str::make('1')->isNumeric());
        $this->assertTrue(Str::make('-1')->isNumeric());
        $this->assertTrue(Str::make('' . PHP_INT_MIN)->isNumeric());
        $this->assertTrue(Str::make('' . PHP_INT_MAX)->isNumeric());
        $this->assertTrue(Str::make('0.0')->isNumeric());
        $this->assertTrue(Str::make('1.0')->isNumeric());
        $this->assertTrue(Str::make('-1.0')->isNumeric());
        $this->assertTrue(Str::make('' . M_PI)->isNumeric());

        $this->assertFalse(Str::make('')->isNumeric());
        $this->assertFalse(Str::make('a')->isNumeric());
        $this->assertFalse(Str::make("\t")->isNumeric());
    }

    public function testIsNumericInt(): void
    {
        $this->assertTrue(Str::make('0')->isNumericInt());
        $this->assertTrue(Str::make('1')->isNumericInt());
        $this->assertTrue(Str::make('-1')->isNumericInt());
        $this->assertTrue(Str::make('' . PHP_INT_MIN)->isNumericInt());
        $this->assertTrue(Str::make('' . PHP_INT_MAX)->isNumericInt());

        $this->assertFalse(Str::make('a')->isNumericInt());
        $this->assertFalse(Str::make('0.0')->isNumericInt());
        $this->assertFalse(Str::make('1.0')->isNumericInt());
        $this->assertFalse(Str::make('-1.0')->isNumericInt());
        $this->assertFalse(Str::make('' . M_PI)->isNumericInt());
    }

    public function testIsNumericFloat(): void
    {
        $this->assertTrue(Str::make('0.0')->isNumericFloat());
        $this->assertTrue(Str::make('1.0')->isNumericFloat());
        $this->assertTrue(Str::make('-1.0')->isNumericFloat());
        $this->assertTrue(Str::make('' . M_PI)->isNumericFloat());

        $this->assertFalse(Str::make('a')->isNumericFloat());
        $this->assertFalse(Str::make('1')->isNumericFloat());
        $this->assertFalse(Str::make('-1')->isNumericFloat());
        $this->assertFalse(Str::make('' . PHP_INT_MIN)->isNumericFloat());
        $this->assertFalse(Str::make('' . PHP_INT_MAX)->isNumericFloat());
    }
}
