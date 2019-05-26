<?php

declare(strict_types = 1);

namespace Tests\Unit;

use DateTime;
use InvalidArgumentException;
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

    public function testIn(): void
    {
        $this->assertTrue(Arr::in($this->asset[0], 0, 1, 2, 3, 4, 5));
        $this->assertTrue(Arr::in($this->asset[0], 0));
        $this->assertTrue(Arr::in($this->asset[0], 5));
        $this->assertTrue(Arr::in($this->asset[0], 5, 2, 1));

        $this->assertFalse(Arr::in($this->asset[0], 0, 1, 2, 3, 4, 5, true));
        $this->assertFalse(Arr::in($this->asset[0], 0, '0'));
        $this->assertFalse(Arr::in($this->asset[0], -1));
        $this->assertFalse(Arr::in($this->asset[0], null));

        $this->expectExceptionObject(new InvalidArgumentException('Haystack array cannot be empty.', 400));

        Arr::in($this->asset[0]);
    }

    public function testInAny(): void
    {
        $this->assertTrue(Arr::inAny($this->asset[0], 0, 7, 8, null, false));
        $this->assertTrue(Arr::inAny($this->asset[0], 54, 0.0, false, true, 0));

        $this->assertFalse(Arr::inAny($this->asset[0], true, false, null, 0.0, -1, new DateTime));

        $this->expectExceptionObject(new InvalidArgumentException('Haystack array cannot be empty.', 400));

        Arr::in($this->asset[0]);
    }

    public function testInLoose(): void
    {
        $this->assertTrue(Arr::inLoose($this->asset[0], 0, 1, 2, 3, 4, 5));
        $this->assertTrue(Arr::inLoose($this->asset[0], '0', '1', '2', '3', '4', '5'));
        $this->assertTrue(Arr::inLoose($this->asset[0], 0, '0', '1', 5));
        $this->assertTrue(Arr::inLoose($this->asset[0], true));
        $this->assertTrue(Arr::inLoose($this->asset[0], false));
        $this->assertTrue(Arr::inLoose($this->asset[0], null));

        $this->assertFalse(Arr::inLoose($this->asset[0], 0, 1, -1));
        $this->assertFalse(Arr::inLoose($this->asset[0], 98, 66, 1, 2, 3));

        $this->expectExceptionObject(new InvalidArgumentException('Haystack array cannot be empty.', 400));

        Arr::in($this->asset[0]);
    }

    public function testInLooseAny(): void
    {
        $this->assertTrue(Arr::inLooseAny($this->asset[0], 0));
        $this->assertTrue(Arr::inLooseAny($this->asset[0], -1, -2, false, true, null, 0));
        $this->assertTrue(Arr::inLooseAny($this->asset[0], true, false, 33, 21));
        $this->assertTrue(Arr::inLooseAny($this->asset[0], true, 44));

        $this->assertFalse(Arr::inLooseAny($this->asset[0], 33, 44));

        $this->expectExceptionObject(new InvalidArgumentException('Haystack array cannot be empty.', 400));

        Arr::in($this->asset[0]);
    }

    public function testMap(): void
    {
        $map = Arr::map($this->asset, [
            '3.id' => 'id',
            '0.3'  => 'number.3',
            '0.5'  => 'number.5',
            '2'    => '2',
        ]);

        $this->tester->assertArr([
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
            'id'     => 1,
            'number' => [
                3 => 3,
                5 => 5,
            ],
            2        => [],
        ], $map);
    }

    public function testMapEach(): void
    {
        $values = [
            ['user' => ['id' => 1, 'name' => 'Test 1'], 1],
            ['user' => ['id' => 2, 'name' => 'Test 2'], 2],
            ['user' => ['id' => 3, 'name' => 'Test 3'], 3],
        ];

        $mappings = [
            'user.id'   => 'id',
            'user.name' => 'user_name'
        ];

        $mapped      = Arr::mapEach($values, $mappings);
        $mappedKeyed = Arr::mapEach($values, $mappings, 'id');

        $this->assertEquals([
            ['user' => ['id' => 1, 'name' => 'Test 1'], 1, 'id' => 1, 'user_name' => 'Test 1'],
            ['user' => ['id' => 2, 'name' => 'Test 2'], 2, 'id' => 2, 'user_name' => 'Test 2'],
            ['user' => ['id' => 3, 'name' => 'Test 3'], 3, 'id' => 3, 'user_name' => 'Test 3'],
        ], $mapped);

        $this->assertEquals([
            ['user' => ['id' => 1, 'name' => 'Test 1'], 1, 'id' => 1, 'user_name' => 'Test 1'],
            ['user' => ['id' => 2, 'name' => 'Test 2'], 2, 'id' => 2, 'user_name' => 'Test 2'],
            ['user' => ['id' => 3, 'name' => 'Test 3'], 3, 'id' => 3, 'user_name' => 'Test 3'],
        ], $mappedKeyed);
    }

    public function testMapOnly(): void
    {
        $map = Arr::mapOnly($this->asset, [
            '3.id' => 'id',
            '0.3'  => 'number.3',
            '0.5'  => 'number.5',
            '2'    => '2',
        ]);

        $this->tester->assertArr([
            'id'     => 1,
            'number' => [
                3 => 3,
                5 => 5,
            ],
            2        => [],
        ], $map);
    }

    public function testMapEachOnly(): void
    {
        $values = [
            ['user' => ['id' => 1, 'name' => 'Test 1']],
            ['user' => ['id' => 2, 'name' => 'Test 2']],
            ['user' => ['id' => 3, 'name' => 'Test 3']],
        ];

        $mappings = ['user.id' => 'id'];

        $mapped      = Arr::mapEach($values, $mappings);
        $mappedKeyed = Arr::mapEach($values, $mappings, 'id');

        $this->assertEquals([
            ['id' => 1, 'user_name' => 'Test 1'],
            ['id' => 2, 'user_name' => 'Test 2'],
            ['id' => 3, 'user_name' => 'Test 3']
        ], $mapped);

        $this->assertEquals([
            1 => ['id' => 1, 'user_name' => 'Test 1'],
            2 => ['id' => 2, 'user_name' => 'Test 2'],
            3 => ['id' => 3, 'user_name' => 'Test 3']
        ], $mappedKeyed);
    }

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
