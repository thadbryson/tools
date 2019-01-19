<?php

declare(strict_types = 1);

namespace Tests\Unit;

use Tool\Support\Cast;
use function ucfirst;
use const M_PI;
use const PHP_INT_MAX;
use const PHP_INT_MIN;

class CastTest extends \Codeception\Test\Unit
{
    /**
     * @dataProvider data
     */
    public function testCast(string $type, $value, $expected): void
    {
        $method = 'to' . ucfirst($type);

        if ($type === 'bool') {
            $method = 'toBoolean';
        }
        elseif ($type === 'int') {
            $method = 'toInteger';
        }

        $this->assertEquals($expected, Cast::{$method}($value));

        $this->assertEquals($expected, Cast::cast($value, $type));
        $this->assertEquals(['item' => $expected], Cast::all(['item' => $value], ['item' => $type]));
    }

    public function data(): array
    {
        return [
            // Handle NULL values
            ['bool', null, null],
            ['int', null, null],
            ['float', null, null],
            ['string', null, null],
            ['array', null, null],

            ['bool', true, true],
            ['bool', false, false],
            ['bool', 1, true],
            ['bool', '1', true],
            ['bool', 0, false],
            ['bool', '0', false],
            ['bool', 1.0, null],
            ['bool', '', null],
            ['bool', 'true', null],
            ['bool', 'TRUE', null],
            ['bool', 'false', null],
            ['bool', 'FALSE', null],
            ['bool', [], null],

            ['int', 0, 0],
            ['int', 1, 1],
            ['int', PHP_INT_MIN, PHP_INT_MIN],
            ['int', PHP_INT_MAX, PHP_INT_MAX],
            ['int', -1, -1],
            ['int', 1000000, 1000000],
            ['int', '0', 0],
            ['int', '1', 1],
            ['int', '1000000', 1000000],
            ['int', true, null],
            ['int', false, null],
            ['int', 'true', null],
            ['int', [], null],

            ['float', 0.0, 0.0],
            ['float', 1.0, 1.0],
            ['float', -1.0, -1.0],
            ['float', 0, 0],
            ['float', 1, 1.0],
            ['float', 98333, 98333.0],
            ['float', M_PI, M_PI],
            ['float', 3832.4332, 3832.4332],
            ['float', '0.0', '0.0'],
            ['float', '1.0', '1.0'],
            ['float', '-6444.3333', '-6444.3333'],
            ['float', true, null],
            ['float', false, null],
            ['float', '', null],
            ['float', 'true', null],
            ['float', [], null],

            ['string', '', ''],
            ['string', ' ', ' '],
            ['string', "\t\n\t\t\t   \t\n", "\t\n\t\t\t   \t\n"],
            ['string', 'test', 'test'],
            ['string', 1, '1'],
            ['string', 1.1, '1.1'],
            ['string', M_PI, '3.1415926535898'],
            ['string', true, null],
            ['string', false, null],
            ['string', [], null],

            ['array', [], []],
            ['array', [null], [null]],
            ['array', [1, 2, 3], [1, 2, 3]],
            ['array', 0.0, [0.0]],
            ['array', 1.0, [1.0]],
            ['array', -1.0, [-1.0]],
            ['array', 0, [0]],
            ['array', 1, [1.0]],
            ['array', 98333, [98333.0]],
            ['array', M_PI, [M_PI]],
            ['array', 3832.4332, [3832.4332]],
            ['array', '0.0', ['0.0']],
            ['array', '1.0', ['1.0']],
            ['array', '-6444.3333', ['-6444.3333']],
            ['array', true, [true]],
            ['array', false, [false]],
        ];
    }
}
