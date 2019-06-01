<?php

declare(strict_types = 1);

namespace Tests\Unit;

use Tests\Support\Stubs\UserStub;
use Tool\Collection;

class CollectionTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var Collection
     */
    private $coll;

    /**
     * @var Collection
     */
    private $col2;

    public function _before(): void
    {
        $this->coll = new Collection([
            [0, 1, 2, 3, 4, 5],
            ['one', 'two', 'three'],
            [],
            [
                'id'    => 1, 'name' => '',
                'other' => [
                    'addr' => '101 Main',
                    'zip'  => '10000',
                ],
            ],
            [true, false, null],
        ]);

        $this->col2 = new Collection([
            'id'   => 1,
            'code' => 'abc',
            'some' => [
                'addr'    => 101,
                'zip'     => 20000,
                'friends' => ['Tom', 'Tina', 'Jenny'],
            ],
        ]);
    }

    public function testDefaults(): void
    {
        $this->tester
            ->assertArr($this->col2, $this->col2->defaults(['code' => 3, 'some' => 4]))
            ->assertArr([
                'id'    => 1,
                'code'  => 'abc',
                'some'  => [
                    'addr'    => 101,
                    'zip'     => 20000,
                    'friends' => ['Tom', 'Tina', 'Jenny'],
                    'zip2'    => 4,
                ],
                'other' => 3,
            ], $this->col2->defaults(['other' => 3, 'some.zip2' => 4]));
    }

    public function testReset(): void
    {
        $this->tester
            ->assertArr([], $this->coll->reset([]))
            ->assertArr([1, null, true, false], $this->coll->reset([1, null, true, false]));
    }

    public function testClear(): void
    {
        $this->tester->assertArr([], $this->coll->clear());
    }

    public function testGetMany(): void
    {
        $this->tester->assertArr([
            'id'        => 1,
            'other'     => null,
            'code'      => 'abc',
            'some.addr' => 101,
        ], $this->col2->getMany('id', 'other', 'code', 'some.addr'));
    }

    public function testGetManyOrDefault(): void
    {
        $this->tester->assertArr([
            'id'        => 1,
            'other'     => 'place',
            'my'        => null,
            'code'      => 'abc',
            'some.addr' => 101,
        ], $this->col2->getManyOrDefault([
            'id'        => 1,
            'other'     => 'place',
            'my'        => null,
            'code'      => 'abc',
            'some.addr' => 101,
        ]));
    }

    public function testSetMany(): void
    {
        $expected = $this->col2->toArray();

        $expected['some']['state'] = 'NC';
        $expected[5]               = 6;

        $this->tester->assertArr($expected, $this->col2->setMany([
            'some.state' => 'NC',
            5            => 6,
        ]));
    }

    public function testActOnAll(): void
    {
        $this->tester->assertArr([
            'id'   => 1,
            'code' => 1,
            'some' => [
                'addr'    => 1,
                'zip'     => 1,
                'friends' => [1, 1, 1],
            ],
        ], $this->col2->actOnAll(function () {
            return 1;
        }));
    }

    public function testActOnAllIf(): void
    {
        $this->tester->assertArr([
            'id'   => 100,
            'code' => 'abc',
            'some' => [
                'addr'    => 101,
                'zip'     => 20000,
                'friends' => ['Tom', 'Tina', 'Jenny'],
            ],
        ], $this->col2->actOnAllIf(
            function () {
                return 100;
            },
            function ($value, $key) {

                return $key === 'id' && (string) $value === '1';
            }
        ));
    }

    public function testTrimEverything(): void
    {
        $coll = $this->coll->toArray();

        $coll[1][0]    = '   ' . $coll[1][0] . ' ';
        $coll[3]['id'] .= '    ';

        $this->tester->assertArr($this->coll, Collection::make($coll)->trimEverything());
    }

    public function testUtf8Everything(): void
    {
        $coll = Collection::make([
            'a', 1, 2, [
                'abc', 'def', 1, 2, 3
            ],
            true,
            false,
            null
        ])->utf8Everything();

        $this->assertEquals([
            'a', 1, 2, [
                'abc', 'def', 1, 2, 3
            ],
            true,
            false,
            null
        ], $coll->toArray());
    }
}
