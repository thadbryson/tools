<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\Collection;

use Tests\Support\Stubs\ModelStub;
use Tool\Collection;

/**
 * Class KeyMethodsTraitTest
 *
 */
class KeyTraitTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var Collection
     */
    private $coll;

    /**
     * @var Collection
     */
    private $coll2;

    private $undot = [
        'id'   => 1,
        'code' => 'abc',
        'some' => [
            'addr'    => 101,
            'zip'     => 20000,
            'friends' => ['Tom', 'Tina', 'Jenny'],
        ],
    ];

    private $dotted = [
        'id'             => 1,
        'code'           => 'abc',
        'some.addr'      => 101,
        'some.zip'       => 20000,
        'some.friends.0' => 'Tom',
        'some.friends.1' => 'Tina',
        'some.friends.2' => 'Jenny',
    ];

    public function _before(): void
    {
        $this->coll = new Collection($this->undot);

        $this->coll2 = new Collection([
            [],
            [],
            []
        ]);
    }

    public function testDot(): void
    {
        $this->tester->assertArr($this->dotted, $this->coll->dot());
        $this->tester->assertArr([
            'pre_id'             => 1,
            'pre_code'           => 'abc',
            'pre_some.addr'      => 101,
            'pre_some.zip'       => 20000,
            'pre_some.friends.0' => 'Tom',
            'pre_some.friends.1' => 'Tina',
            'pre_some.friends.2' => 'Jenny',
        ], $this->coll->dot('pre_'), 'Add a prepended string to the DOTTED array.');
    }

    public function testUndot(): void
    {
        $dot = $this->coll->dot();

        $this->tester->assertArr($this->dotted, $dot);
        $this->tester->assertArr($this->undot, $dot->undot());
    }

    public function testOnlyEach(): void
    {
        $model          = new ModelStub;
        $model->id      = 2;
        $model->another = 'some';
        $model->what    = false;

        $table = Collection::make([
            $model,
            ['id' => 1, 'first' => 1, 'another' => 1, 'some' => 'other'],
            ['id' => 6, 'first' => 1, 'another' => 3, 'maybe' => 'this']
        ])->onlyEach('id', 'another');

        $this->tester->assertArr([
            ['id' => 2, 'another' => 'some'],
            ['id' => 1, 'another' => 1],
            ['id' => 6, 'another' => 3]
        ], $table);
    }
}
