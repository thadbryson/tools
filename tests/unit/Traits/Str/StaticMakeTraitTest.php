<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\Str;

use Tool\Str;
use Tool\StrStatic;

class StaticMakeTraitTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function testStaticMake(): void
    {
        $this->tester
            ->assertStr(Str::make('', 'ISO-8859-1'), '', 'ISO-8859-1')
            ->assertStr(Str::make(' '), ' ');
    }

    public function testStaticImplode(): void
    {
        $this->tester
            ->assertStr(Str::implode(',', ['some', 'stuff']), 'some,stuff')
            ->assertStr(Str::implode('', ['A', 'B', 'C'], 'UTF-16'), 'ABC', 'UTF-16');
    }

    public function testStaticUuid(): void
    {
        $uuid1 = StrStatic::uuid();
        $uuid2 = StrStatic::uuid();

        $this->assertNotEquals((string) $uuid1, (string) $uuid2, 'All UUIDs should be unique. Never repeat.');

        $this->assertStringContainsString('-', $uuid1);
        $this->assertEquals(36, strlen($uuid1), 'UUID made: ' . $uuid1);
    }

    public function testStaticRandom(): void
    {
        $this->tester->assertStr(Str::make('')->random(0, 'ISO-8859-1'), '', 'ISO-8859-1');

        $this->assertEquals(1, Str::make('')->random(1)->length());
        $this->assertEquals(1, strlen(StrStatic::random(1)));
    }
}