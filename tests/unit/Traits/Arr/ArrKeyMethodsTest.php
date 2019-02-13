<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\Arr;

use Tool\Arr;

class ArrKeyMethodsTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    private $original = [
        'id'   => 1,
        'name' => 2,
        'some' => [
            'person' => '',
            'other'  => 'maybe',
        ],
    ];

    public function testUndot(): void
    {
        $this->assertEquals($this->original, Arr::undot([
            'id'          => 1,
            'name'        => 2,
            'some.person' => '',
            'some.other'  => 'maybe',
        ]));

        $this->assertEquals($this->original, Arr::undot([
            'what.id'          => 1,
            'what.name'        => 2,
            'what.some.person' => '',
            'what.some.other'  => 'maybe',
        ], 'what.'));
    }

    public function testKeysDot(): void
    {
        $this->assertEquals(['id', 'name', 'some.person', 'some.other'], Arr::keysDot($this->original));

        $this->assertEquals(['id', 'name', 'some.person', 'some.other'], Arr::keysDot([
            'id'          => 1,
            'name'        => 2,
            'some.person' => '',
            'some.other'  => 'maybe',
        ]));
    }

    public function testIsNotAssoc(): void
    {
        $this->assertTrue(Arr::isNotAssoc([1, 2, 3]));
        $this->assertEquals(Arr::isAssoc([1, 2, 3]) === false, Arr::isNotAssoc([1, 2, 3]));

        $this->assertFalse(Arr::isNotAssoc([1, 2, 'id' => true]));
        $this->assertEquals(Arr::isAssoc([1, 2, 'id' => true]), Arr::isNotAssoc([1, 2, 'id' => true]) === false);
    }
}
