<?php

declare(strict_types = 1);

use Tool\Support\Arr;

class ArrTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    private static $table = [
        [0, 1, 2, 3, 4],
        [6, 7, 8, 11, 12],
        [18, 20, 50, 111, 298],
    ];

    private function dataDotsUndots(): array
    {
        return [
            [[], []],
        ];
    }

    /**
     * @dataProvider dataDotsUndots
     */
    public function testUndot(array $dots, array $undots): void
    {
        $this->assertEquals($undots, Arr::undot($dots));
        $this->assertEquals($dots, Arr::dot($undots));
        $this->assertEquals($undots, Arr::undot(Arr::dot($undots)));
    }

    private function dataOrderKeys(): array
    {
        return [
            [[], [], '0', '1', '2'],
        ];
    }

    /**
     * @dataProvider dataOrderKeys
     *
     * @param array  $expected
     * @param array  $keys
     * @param string ...$orderKeys
     */
    public function testOrderKeys(array $expected, array $keys, string ...$orderKeys): void
    {
        $this->assertEquals($expected, Arr::orderKeys($keys, ...$orderKeys));
    }

    private function dataColumn(): array
    {
        return [];
    }

    /**
     * @dataProvider dataColumn
     */
    public function testColumn(): void
    {

    }

    private function dataMerge(): array
    {
        return [];
    }

    /**
     * @dataProvider dataMerge
     */
    public function testMerge(): void
    {
        $merged = [];

        $this->assertEquals($merged, Arr::merge([], [], []));
    }

    private function dataReplace(): array
    {
        return [];
    }

    /**
     * @dataProvider dataReplace
     */
    public function testReplace(): void
    {
        $this->assertEquals([0, 2, 2], Arr::replace(
            [0, 1, 2],
            [1, 2],
            [0]
        ));
    }

    private function dataCast(): array
    {
        return [];
    }

    public function testCast(): void
    {
        $array1 = [];
        $array2 = true;
        $array3 = null;

        $array1 = Arr::cast($array1);
        $array2 = Arr::cast($array2);
        $array3 = Arr::cast($array3);

        $this->assertEquals([], $array1);
        $this->assertEquals([true], $array2);
        $this->assertEquals([null], $array3);
    }

    /**
     * @param array  $expected
     * @param array  $array
     * @param string ...$keys
     */
    public function testRemove(): void
    {
        $this->assertEquals([], Arr::remove(static::$table, '0', '1', '2'));
        $this->assertEquals([[6, 7, 8, 11, 12]], Arr::remove(static::$table, '0', '2'));
        $this->assertEquals([
            [0, 3, 4],
            [18, 20, 111],
        ], Arr::remove(static::$table, '1', '1.3', '0.2', '0.1', '3.5', 'nope', '2.2', '2.4'));
    }

    private function dataHasAll(): array
    {
        return [
            [true, '0', '1', '2'],
            [true, '0.0', '0.1', '0.2', '0.3', '0.4', '1.0', '2.4', '1.4', '2.2', '1.2'],
            [false, 'nope'],
            [false, '0.5'],
            [false, '0.2', '0.0', '0.4', '3'],
        ];
    }

    /**
     * @dataProvider dataHasAll
     *
     * @param bool   $expected
     * @param string ...$keys
     */
    public function testHasAll(bool $expected, string ...$keys): void
    {
        $this->assertEquals($expected, Arr::hasAll(static::$table, ...$keys), 'Keys: ' . implode(', ', $keys));
    }

    private function dataGetAll(): array
    {
        return [

        ];
    }

    /**
     * @dataProvider dataGetAll
     */
    public function testGetAll(): void
    {
        $gotten = Arr::getAll(static::$table, '0', '0.3', '2.3', '1.2');

        $this->assertEquals([
            [0, 1, 2, 3, 4],
            3,
            111,
            8
        ], $gotten, json_encode($gotten, JSON_PRETTY_PRINT));
    }

    private function dataIsEmpty(): array
    {
        return [
            [[]],
            [[], []],
            [[], [], []],
            [[], [], [], [], [], [], [], [], [], [], [], [], [], [], [], [], [], []]
        ];
    }

    /**
     * @dataProvider dataIsEmpty
     */
    public function testIsEmpty(array ...$empties): void
    {
        $this->assertTrue(Arr::isEmpty(...$empties));
        $this->assertFalse(Arr::isNotEmpty(...$empties));
    }

    private function dataIsNotEmpty(): array
    {
        return [
            [[null]],
            [[null], []],
            [[false]],
            [[false], []],
            [[0]],
            [[0], []],
            [[], [null]],
            [[], [false]],
            [[], [0]],
            [[], [], [], [], [], [], [], [], [], [null], [], [], [], [], [], [], [], []]
        ];
    }

    /**
     * @dataProvider dataIsNotEmpty
     */
    public function testIsNotEmpty(array ...$notEmpties): void
    {
        $this->assertTrue(Arr::isNotEmpty(...$notEmpties));
        $this->assertFalse(Arr::isEmpty(...$notEmpties));
    }

    public function testWhereValue(): void
    {

    }

    public function testWhereValueStrict(): void
    {

    }

    public function testWhereRegex(): void
    {
    }

    public function testWhereNotRegex(): void
    {

    }

    public function testWhereLike(): void
    {

    }

    public function testWhereNotLike(): void
    {

    }
}