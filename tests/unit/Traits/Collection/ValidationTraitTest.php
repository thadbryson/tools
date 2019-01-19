<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\Collection;

use Tool\Support\Collection;

/**
 * Class ValidationTraitTest
 */
class ValidationTraitTest extends \Codeception\Test\Unit
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

    public function testValidate(): void
    {
        $result = $this->coll->validate(['id' => 'required|integer']);

        $this->assertTrue($result->isSuccess());
    }

    public function testAssertEquals(): void
    {
        $result = $this->coll->assertEquals([
            'id'        => 1,
            'some.addr' => 101
        ]);

        $this->tester->assertArr([
            'id'   => 1,
            'code' => 'abc',
            'some' => [
                'addr'    => 101,
                'zip'     => 20000,
                'friends' => ['Tom', 'Tina', 'Jenny']
            ]
        ], $result);

        $this->tester->expectException(
            new \InvalidArgumentException('Value \'1\' is not what is expected for key: id.'),
            function () {
                $this->coll->assertEquals(['id' => '1']);
            }
        );
    }
}
