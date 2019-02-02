<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\Collection;

use Tool\Collection;

/**
 * Class ValidationTraitTest
 */
class ValidationTraitTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    private $items = [
        'id'   => 1,
        'code' => 'abc',
        'some' => [
            'addr'    => 101,
            'zip'     => 20000,
            'friends' => ['Tom', 'Tina', 'Jenny'],
        ],
    ];

    /**
     * @var Collection
     */
    private $coll;

    public function _before(): void
    {
        $this->coll = new Collection($this->items);
    }

    public function testValidate(): void
    {
        $result = $this->coll->validate(['id' => 'required|integer']);

        $this->assertTrue($result->isSuccess());
    }

    public function testAssertEquals(): void
    {
        $result = $this->coll
            ->assertEquals('id', 1)
            ->assertEquals('some.addr', 101);

        $this->tester
            ->assertArr($this->items, $result)
            ->expectThrowable(
                \InvalidArgumentException::class,
                function () {
                    $this->coll->assertEquals('id', '10');
                }
            );
    }

    public function testAssertEqualsAll(): void
    {
        $result = $this->coll->assertEqualsAll([
            'id'        => 1,
            'some.addr' => 101,
        ]);

        $this->tester
            ->assertArr($this->items, $result)
            ->expectThrowable(
                \InvalidArgumentException::class,
                function () {
                    $this->coll->assertEqualsAll(['id' => '10']);
                }
            );
    }
}
