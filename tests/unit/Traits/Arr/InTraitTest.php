<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\Arr;

use DateTime;
use InvalidArgumentException;
use Tool\Arr;

class InTraitTest extends \Codeception\Test\Unit
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
}
