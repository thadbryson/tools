<?php

declare(strict_types = 1);

namespace Tests\Unit\Arr;

use Tool\Support\Arr;

class ArrKeyMethodsTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected $original = [
        'id'   => 1,
        'name' => 2,
        'some' => [
            'person' => '',
            'other'  => 'maybe'
        ]
    ];

    public function testUndot(): void
    {
        $this->assertEquals($this->original, Arr::undot([
            'id'          => 1,
            'name'        => 2,
            'some.person' => '',
            'some.other'  => 'maybe'
        ]));
    }

    public function testKeysDot(): void
    {
        $this->assertEquals(['id', 'name', 'some.person', 'some.other'], Arr::keysDot([
            'id'          => 1,
            'name'        => 2,
            'some.person' => '',
            'some.other'  => 'maybe'
        ]));
    }

    public function testRenameKeys(): void
    {
        $this->assertEquals([
            'id'    => 1,
            'code'  => 2,
            'other' => [
                'person' => '',
                'nice'   => 'maybe'
            ]
        ], Arr::renameKeys($this->original, [
            'name'        => 'code',
            'some'        => 'other',
            'other.other' => 'other.nice'
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
