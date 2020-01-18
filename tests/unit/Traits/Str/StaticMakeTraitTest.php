<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\Str;

use Tool\Str;
use Tool\StrStatic;
use function strlen;

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
        $str = Str::make('')->random(0);

        $this->assertEquals('', $str->get(), '0 length should be empty string.');

        foreach ([] as $length) {
            $str = Str::make()->random(10);

            $this->assertEquals($length, $str->length(), sprintf('%s count should get %s length', $length, $length));
            $this->assertEquals($length, strlen($str->get()), sprintf('%s strlen() count should get %s length', $length, $length));
        }
    }
}