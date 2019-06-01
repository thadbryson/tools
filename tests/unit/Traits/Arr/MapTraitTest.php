<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\Arr;

use Tool\Arr;
use function array_keys;

class MapTraitTest extends \Codeception\Test\Unit
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

        $this->assertEquals([
            ['user' => ['id' => 1, 'name' => 'Test 1'], 1, 'id' => 1, 'user_name' => 'Test 1'],
            ['user' => ['id' => 2, 'name' => 'Test 2'], 2, 'id' => 2, 'user_name' => 'Test 2'],
            ['user' => ['id' => 3, 'name' => 'Test 3'], 3, 'id' => 3, 'user_name' => 'Test 3'],
        ], Arr::mapEach($values, $mappings));

        $this->assertEquals([
            ['user' => ['id' => 1, 'name' => 'Test 1'], 1, 'id' => 1, 'user_name' => 'Test 1', 'key' => 0],
            ['user' => ['id' => 2, 'name' => 'Test 2'], 2, 'id' => 2, 'user_name' => 'Test 2', 'key' => 1],
            ['user' => ['id' => 3, 'name' => 'Test 3'], 3, 'id' => 3, 'user_name' => 'Test 3', 'key' => 2],
        ], Arr::mapEach($values, $mappings, 'key'));
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

        $this->assertEquals([
            ['id' => 1],
            ['id' => 2],
            ['id' => 3]
        ], Arr::mapEachOnly($values, $mappings));

        $this->assertEquals([
            ['id' => 1, 'key' => 0],
            ['id' => 2, 'key' => 1],
            ['id' => 3, 'key' => 2]
        ], Arr::mapEachOnly($values, $mappings, 'key'));
    }
}
