<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\Collection;

use Tool\Collection;

/**
 * Class KeyMethodsTraitTest
 *
 */
class KeyIndexTraitTest extends \Codeception\Test\Unit
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
                'friends' => ['Tom', 'Tina', 'Jenny'],
            ],
        ]);
    }

    public function testInsertAt(): void
    {
        $coll = $this->coll->insertAt(1, 1, true, false, null, 0);

        $this->tester->assertArr([
            'id'   => 1,
            1, true, false, null, 0,
            'code' => 'abc',
            'some' => [
                'addr'    => 101,
                'zip'     => 20000,
                'friends' => ['Tom', 'Tina', 'Jenny'],
            ],
        ], $coll);
    }

    public function testSetAt(): void
    {
        $coll = $this->coll->setAt(0, 'me');

        $this->tester->assertArr([
            0      => 'me',
            'code' => 'abc',
            'some' => [
                'addr'    => 101,
                'zip'     => 20000,
                'friends' => ['Tom', 'Tina', 'Jenny'],
            ],
        ], $coll);
    }

    public function testGetAt(): void
    {
        $this->assertNull($this->coll->getAt(5), 'Uses non-defined default NULL');
        $this->assertTrue($this->coll->getAt(5, true), 'Uses given default TRUE');

        $this->assertEquals([
            'addr'    => 101,
            'zip'     => 20000,
            'friends' => ['Tom', 'Tina', 'Jenny'],
        ], $this->coll->getAt(2));
    }

    public function testPullAt(): void
    {
        $this->assertNull($this->coll->pullAt(5), 'Uses non-defined default NULL');
        $this->assertTrue($this->coll->pullAt(5, true), 'Uses given default TRUE');

        $this->assertEquals([
            'addr'    => 101,
            'zip'     => 20000,
            'friends' => ['Tom', 'Tina', 'Jenny'],
        ], $this->coll->pullAt(2));

        $this->assertEquals([
            'id'   => 1,
            'code' => 'abc'
        ], $this->coll->all(), '->coll should of been changed.');
    }
}
