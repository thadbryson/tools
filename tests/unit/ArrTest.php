<?php

declare(strict_types = 1);

namespace Tests\Unit;

use Tool\Arr;

class ArrTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    private $asset = [
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
    ];

    public function testCopy(): void
    {
        $dest = Arr::copy($this->asset, [], '2', 'empty');
        $dest = Arr::copy($this->asset, $dest, '4.1', 'some.false');

        $this->tester->assertArr([
            'empty' => [],
            'some'  => [
                'false' => false,
            ],
        ], $dest);
    }

    public function testMove(): void
    {
        $dest = Arr::move($this->asset, [], '2', 'empty');
        $dest = Arr::move($this->asset, $dest, '4.1', 'some.false');

        $this->tester->assertArr([
            'empty' => [],
            'some'  => [
                'false' => false,
            ],
        ], $dest);

        $this->tester->assertArr([
            0 => [0, 1, 2, 3, 4, 5],
            1 => ['one', 'two', 'three'],
            3 => [
                'id'    => 1, 'name' => '',
                'other' => [
                    'addr' => '101 Main',
                    'zip'  => '10000',
                ],
            ],
            4 => [0 => true, 2 => null],
        ], $this->asset);
    }

    public function testTrimAll(): void
    {
        $asset = $this->asset;

        $asset[1][0]    = '   ' . $asset[1][0] . ' ';
        $asset[3]['id'] .= '    ';

        $this->tester->assertArr($this->asset, Arr::trimAll($asset));
    }

    public function testDefaults(): void
    {
        $defaults = [
            'id'   => 1,
            'code' => null,
            'some' => [
                'addr'  => 101,
                'state' => 'NC',
            ],
        ];

        $actual = $defaults;

        $actual['code']          = 'ABC';
        $actual['some']['state'] = 'PA';

        $this->tester
            ->assertArr([], Arr::defaults([], []))
            ->assertArr($defaults, Arr::defaults([], $defaults))
            ->assertArr($actual, Arr::defaults([
                'code' => 'ABC',
                'some' => [
                    'state' => 'PA',
                ],
            ], $defaults));
    }

    public function testGetMany(): void
    {
        $this->tester
            ->assertArr(['0.2' => 2, '2' => [], '3.other.zip' => '10000'],
                Arr::getMany($this->asset, '0.2', '2', '3.other.zip'))
            ->assertArr(['id' => null, '7' => null], Arr::getMany($this->asset, 'id', '7'));
    }

    public function testIsEmpty(): void
    {
        $this->assertTrue(Arr::isEmpty([]));
        $this->assertTrue(Arr::isEmpty([], []));

        $this->assertFalse(Arr::isEmpty([1]));
        $this->assertFalse(Arr::isEmpty([], [1]));
    }

    public function testIsNotEmpty(): void
    {
        $this->assertFalse(Arr::isNotEmpty([]));
        $this->assertFalse(Arr::isNotEmpty([], []));

        $this->assertTrue(Arr::isNotEmpty([1]));
        $this->assertTrue(Arr::isNotEmpty([], [1]));
    }

    public function testBlacklist(): void
    {
        $this->tester
            ->assertArr([], Arr::blacklist([]))
            ->assertArr($this->asset, Arr::blacklist($this->asset, 'else'))
            ->assertArr($this->asset, Arr::blacklist($this->asset, '2.whatever'));

        $this->tester->expectException(new \InvalidArgumentException('Inivalid key/value pairs found in array.'),
            function () {

                Arr::blacklist($this->asset, '2');
            }
        );
    }

    public function testWhitelist(): void
    {
        $this->tester
            ->assertArr([], Arr::whitelist([]))
            ->assertArr($this->asset, Arr::whitelist($this->asset, '0', '1', '2', '3', '4'));

        $this->tester->expectException(new \InvalidArgumentException('Inivalid key/value pairs found in array.'),
            function () {

                Arr::whitelist($this->asset, '2');
            }
        );
    }
}
