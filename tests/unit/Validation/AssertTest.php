<?php

declare(strict_types = 1);

namespace Tests\Unit\Validation;

use Carbon\Carbon;
use DateTime;
use Tool\Validation\Assert;
use Tool\Validation\Helper;
use function get_class;

/**
 * Test Assert and AssertRules classes. AssertRules will call the
 * corresponding Assert method.
 *
 * Here we call the AssertRules and test for the boolean return.
 */
class AssertTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @dataProvider data
     *
     * @param string $method
     * @param mixed  ...$args
     */
    public function testNull(string $method, $value, ...$args): void
    {
        // NULL for these should throw an Exception.
        $this->tester->expectThrowable(\InvalidArgumentException::class, function () use ($method, $args) {

            Assert::{$method}(null, ...$args);
        });
    }

    /**
     * @dataProvider data
     * @dataProvider dataCustomMethods
     *
     * @param string $method
     * @param mixed  $value
     * @param mixed  ...$args
     */
    public function testValid(string $method, $value, ...$args): void
    {
        // Assert will pass, and return the value tested.
        try {
            $returnedValue = Assert::{$method}($value, ...$args);

            $this->assertSame($returnedValue, $value);
        }
        catch (\Exception $e) {
            $this->fail(sprintf('%s(), %s: %s', $method, get_class($e), $e->getMessage()));
        }
    }

    /**
     * @dataProvider dataFalse
     * @dataProvider dataCustomMethodsFalse
     *
     * @param string $method
     * @param mixed  $value
     * @param mixed  ...$args
     */
    public function testInvalid(string $method, $value, ...$args): void
    {
        $this->tester->expectThrowable(\InvalidArgumentException::class, function () use ($method, $value, $args) {

            Assert::{$method}($value, ...$args);
        });
    }

    public function data(): array
    {
        return [
            ['string', ''],
            ['notEmpty', ' '],
            ['integer', 0],
            ['integerish', 0],
            ['float', 0.0],
            ['numeric', 0],
            ['boolean', true],
            ['boolean', false],
            ['true', true],
            ['false', false],
            ['eq', 100, 100],
        ];
    }

    public function dataCustomMethods(): array
    {
        return [
            ['equals', '', ''],
            ['stringNotEmpty', ' '],
            ['stringNotEmpty', 'test'],
            ['oneOfAType', 1, ['int', 'string', 'bool']],
            ['allOfAnyType', [1.0, 'string'], ['int', 'float', 'string', 'array']],
            ['inArray', '', [1, 2, true, null, false, '']],
            ['notInArray', '', [1, 2, 3, null]],
            ['dotKeyExists', '0.2', [[1, 2, 3], 'a', 'b', 'c']],
            ['notDotKeyExists', 'id', [1, 2, 3]],
            ['isSubclassOf', Carbon::class, DateTime::class],
            ['isSubclassOf', DateTime::class, DateTime::class],
            ['isSubclassOf', new Carbon, DateTime::class],
            ['isSubclassOf', new DateTime, DateTime::class],
            ['classOrObject', \DateTime::class],
            ['methodExists', 'format', new \DateTime],
            ['classOrObject', new \DateTime('2015-01-20')],
            ['fileExtension', __FILE__, 'php'],
            ['fileExtension', __FILE__, '.php'],
        ];
    }

    public function dataFalse(): array
    {
        return [
            ['string', false],
            ['notEmpty', ''],
            ['integer', '7a'],
            ['integerish', '123a'],
            ['float', 5],
            ['numeric', false],
            ['boolean', 1],
            ['boolean', '0'],
            ['notEmpty', ''],
            ['true', false],
            ['false', true],
            ['eq', 100, 101],
        ];
    }

    public function dataCustomMethodsFalse(): array
    {
        return [
            ['equals', '', ' '],
            ['equals', null, ''],
            ['equals', true, false],
            ['equals', [1, 2, 3], []],
            ['equals', '', new \DateTime],
            ['stringNotEmpty', ''],
            ['oneOfAType', [], ['integer', 'string', 'bool']],
            ['allOfAnyType', [1.0, 'string'], ['integer', 'array']],
            ['inArray', 'nope', [1, 2, true, null, false, '']],
            ['notInArray', 1, [1, 2, 3]],
            ['dotKeyExists', '0.5', [[1, 2, 3], 'a', 'b', 'c']],
            ['notDotKeyExists', '0', [1, 2, 3]],
            ['isSubclassOf', DateTime::class, Carbon::class],
            ['isSubclassOf', new DateTime, Carbon::class],
            ['classOrObject', '\\NotAClass'],
            ['classOrObject', 1],
            ['methodExists', 'format ', \DateTime::class],
            ['methodExists', 1, \DateTime::class . 'nah'],
            ['fileExtension', 'word.txt', 'pdf'],
        ];
    }

    /**
     * @dataProvider dataTypeStrings
     */
    public function testTypeToString($value, string $expected): void
    {
        $refl = (new \ReflectionClass(Helper\AssertRules::class))->getMethod('typeString');
        $refl->setAccessible(true);

        $this->assertEquals($expected, $refl->invoke(null, $value));
    }

    public function dataTypeStrings(): array
    {
        return [
            [null, 'null'],
            [true, 'bool'],
            [false, 'bool'],
            [0, 'int'],
            [1, 'int'],
            [-1, 'int'],
            [0.0, 'float'],
            [1.0, 'float'],
            [-1.0, 'float'],
            [[], 'array'],
            [\DateTime::class, '\DateTime'],
            [new \DateTime, '\DateTime'],
            ['', 'string'],
            ['test-string', 'string'],
        ];
    }
}
