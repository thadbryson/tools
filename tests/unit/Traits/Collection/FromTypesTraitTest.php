<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\Collection;

use Illuminate\Http\Request;
use Tool\Collection;

/**
 * Trait FromTypesTraitTest
 *
 */
class FromTypesTraitTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var Request
     */
    private $request;

    public function _before(): void
    {
        $this->request = new Request(
            [
                // GET vars
                'q' => 'some'
            ],
            [
                // POST vars
                'form' => [
                    'namem' => 'Person 1',
                    'type'  => 'Admin'
                ]
            ],
            [],
            [],
            [],
            [
                // SERVER vars
                'REQUEST_METHOD' => 'POST'
            ]
        );
    }

    public function testStaticFromRequest(): void
    {
        $this->assertEquals([
            'q'    => 'some',
            'form' => [
                'namem' => 'Person 1',
                'type'  => 'Admin'
            ]
        ], $this->request->input());

        $this->tester->assertArr([
            'q'    => 'some',
            'form' => [
                'namem' => 'Person 1',
                'type'  => 'Admin'
            ]
        ], Collection::fromRequest($this->request));
    }

    public function testLoadRequest(): void
    {
        $this->tester->assertArr([
            'q'    => 'some',
            'form' => [
                'namem' => 'Person 1',
                'type'  => 'Admin'
            ]
        ], Collection::make()->loadRequest($this->request));
    }

    public function testFromExplodeString(): void
    {
        $this->tester
            ->assertArr(['some', 'list', ' here'], Collection::fromExplodeString(',', 'some,list, here'))
            ->assertArr(['some', 'list, here'], Collection::fromExplodeString(',', 'some,list, here', 2))
            ->assertArr(['some'], Collection::fromExplodeString(',', 'some,list, here', -2))
            ->assertArr(['some,list, here'], Collection::fromExplodeString(',', 'some,list, here', 0));
    }
}
