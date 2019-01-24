<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\Collection;

use Tool\Collection;

/**
 * Class KeyMethodsTraitTest
 *
 */
class KeyMethodsTraitTest extends \Codeception\Test\Unit
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

    public function testDot(): void
    {
        $this->tester->assertArr([
            'id'             => 1,
            'code'           => 'abc',
            'some.addr'      => 101,
            'some.zip'       => 20000,
            'some.friends.0' => 'Tom',
            'some.friends.1' => 'Tina',
            'some.friends.2' => 'Jenny'
        ], $this->coll->dot());

        $this->tester->assertArr([
            'pre_id'             => 1,
            'pre_code'           => 'abc',
            'pre_some.addr'      => 101,
            'pre_some.zip'       => 20000,
            'pre_some.friends.0' => 'Tom',
            'pre_some.friends.1' => 'Tina',
            'pre_some.friends.2' => 'Jenny'
        ], $this->coll->dot('pre_'));
    }

    public function testUndot(): void
    {
        $this->tester->assertArr([
            'id'             => 1,
            'code'           => 'abc',
            'some.addr'      => 101,
            'some.zip'       => 20000,
            'some.friends.0' => 'Tom',
            'some.friends.1' => 'Tina',
            'some.friends.2' => 'Jenny'
        ], $this->coll->dot());

        $this->tester->assertArr([
            'id'   => 1,
            'code' => 'abc',
            'some' => [
                'addr'    => 101,
                'zip'     => 20000,
                'friends' => ['Tom', 'Tina', 'Jenny']
            ]
        ], $this->coll->undot());
    }
}
