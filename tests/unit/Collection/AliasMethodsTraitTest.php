<?php

declare(strict_types = 1);

namespace Tests\Unit\Collection;

use Tool\Support\Collection;

/**
 * Class AliasMethodsTrait
 */
class AliasMethodsTraitTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var Collection
     */
    private $coll;

    public function _before(): void
    {
        $this->coll = new Collection([
            'id'   => 1,
            'code' => 'abc',
            'some' => [
                'addr'    => 101,
                'zip'     => 20000,
                'friends' => ['Tom', 'Tina', 'Jenny']
            ]
        ]);
    }

    public function testSet(): void
    {
        $this->coll
            ->set('some.friends.another', 'Buddy')
            ->set('what', null);

        $this->tester->assertArr([
            'id'   => 1,
            'code' => 'abc',
            'some' => [
                'addr'    => 101,
                'zip'     => 20000,
                'friends' => ['Tom', 'Tina', 'Jenny', 'another' => 'Buddy']
            ],
            'what' => null
        ], $this->coll);
    }

    public function testRemove(): void
    {
        $this->tester->assertArr([
            'code' => 'abc',
            'some' => [
                'addr'    => 101,
                'friends' => ['Tom', 'Tina']
            ]
        ], $this->coll->remove('id', 'some.zip', 'some.friends.2'));
    }

    public function testRemoveFirst(): void
    {
        $this->assertEquals(1, $this->coll->removeFirst());
        $this->assertEquals('abc', $this->coll->removeFirst());
    }

    public function testRemoveLast(): void
    {
        $this->assertEquals([
            'addr'    => 101,
            'zip'     => 20000,
            'friends' => ['Tom', 'Tina', 'Jenny']
        ], $this->coll->removeLast());

        $this->assertEquals('abc', $this->coll->removeLast());
    }
}
