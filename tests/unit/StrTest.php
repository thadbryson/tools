<?php

declare(strict_types = 1);

namespace Tests\Unit;

use Tool\Support\Str;
use UnitTester;
use function json_encode;
use function strlen;
use const JSON_PRETTY_PRINT;

/**
 * Class StrTest
 */
class StrTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public function testStaticMake(): void
    {
        $this->tester
            ->assertStr(Str::make('', 'ISO-8859-1'), '', 'ISO-8859-1')
            ->assertStr(Str::make(' '), ' ');
    }

    public function testExplode(): void
    {
        $str = Str::make('test,comma, string');

        $this->assertEquals(['test', 'comma', ' string'], $str->explode(','), '3 elements.');

        $this->assertEquals(['test,comma, string'], $str->explode(':'), 'Delimiter not in string.');
        $this->assertEquals(['test,comma,', 'string'], $str->explode(' '), 'Delimiter not in string.');
    }

    public function testStaticImplode(): void
    {
        $this->tester
            ->assertStr(Str::implode(',', ['some', 'stuff']), 'some,stuff')
            ->assertStr(Str::implode('', ['A', 'B', 'C'], 'UTF-16'), 'ABC', 'UTF-16');
    }

    public function testStaticUuid(): void
    {
        $uuid1 = Str::uuid();
        $uuid2 = Str::uuid();

        $this->assertNotEquals((string) $uuid1, (string) $uuid2, 'All UUIDs should be unique. Never repeat.');

        $this->assertStringContainsString('-', $uuid1->get());
        $this->assertEquals(36, strlen($uuid1->get()), 'UUID made: ' . $uuid1->get());
    }

    public function testStaticRandom(): void
    {
        $this->tester->assertStr(Str::random(0, 'ISO-8859-1'), '', 'ISO-8859-1');

        $this->assertEquals(1, Str::random(1)->length());
        $this->assertEquals(1, strlen((string) Str::random(1)));
    }

    public function testGet(): void
    {
        $str = Str::make(' what??? ');

        $this->assertEquals(' what??? ', $str->get());
        $this->assertEquals(' what??? ', $str->__toString());
        $this->assertEquals(' what??? ', (string) $str);
    }

    public function testIsEmpty(): void
    {
        $this->assertTrue(Str::make('')->isEmpty());
        $this->assertFalse(Str::make(' ')->isEmpty());
        $this->assertFalse(Str::make('a string here')->isEmpty());
    }

    public function testIsNotEmpty(): void
    {
        $this->assertFalse(Str::make('')->isNotEmpty());
        $this->assertTrue(Str::make(' ')->isNotEmpty());
        $this->assertTrue(Str::make('a string here')->isNotEmpty());
    }

    public function testBeforeSubstr(): void
    {
        $this->tester
            ->assertStr(Str::make('test sub')->beforeSubstr('sub'), 'test ')
            ->assertStr(Str::make('sub test sub')->beforeSubstr('sub', 1), 'sub test ');
    }

    public function testAfterSubstr(): void
    {
        $this->tester
            ->assertStr(Str::make('test sub')->afterSubstr('test'), ' sub')
            ->assertStr(Str::make('sub test sub')->afterSubstr('sub', 1), '');
    }

    /**
     * @dataProvider dataJsonVariables
     *
     * @param mixed $var
     */
    public function testJsonDecode($var): void
    {
        $json = json_encode($var);

        $this->assertEquals($var, Str::make($json)->jsonDecode());
    }

    /**
     * @dataProvider dataJsonVariables
     *
     * @param mixed $var
     */
    public function testJsonDecodeOptions($var): void
    {
        $json = json_encode($var, JSON_PRETTY_PRINT);

        $this->assertEquals($var, Str::make($json)->jsonDecodeOptions(true, JSON_PRETTY_PRINT));
    }

    public function testJsonDecodeException(): void
    {
        $this->tester->expectException(new \InvalidArgumentException('String is not valid JSON: [ test: 1 ]'),
            function () {

                Str::make('[ test: 1 ]')->jsonDecodeOptions(false, JSON_PRETTY_PRINT);
            }
        );

        $this->assertNotEquals(JSON_ERROR_NONE, json_last_error(), 'JSON error: ' . json_last_error());
    }

    public function dataJsonVariables(): array
    {
        return [
            [[]],
            [''],
            [true],
            [false],
            [null],
            ['string'],
            [['id' => 1, 'name' => 'Test', 1, 2, 3, 'other' => ['info' => 'some', 'stuff' => 'here']]],
        ];
    }

    public function testPlural(): void
    {
        $this->tester
            ->assertStr(Str::make('apple')->plural(), 'apples')
            ->assertStr(Str::make('apple')->plural(1), 'apple')
            ->assertStr(Str::make('apple')->plural(0), 'apples');
    }

    public function testGetter(): void
    {
        $this->tester
            ->assertStr(Str::make('var')->getter(), 'getVar')
            ->assertStr(Str::make('var')->getter('Attr'), 'getVarAttr')
            ->assertStr(Str::make('my_var')->getter(), 'getMyVar')
            ->assertStr(Str::make('my_var')->getter('Attr'), 'getMyVarAttr');
    }

    public function testSetter(): void
    {
        $this->tester
            ->assertStr(Str::make('var')->setter(), 'setVar')
            ->assertStr(Str::make('var')->setter('Attr'), 'setVarAttr')
            ->assertStr(Str::make('my_var')->setter(), 'setMyVar')
            ->assertStr(Str::make('my_var')->setter('Attr'), 'setMyVarAttr');
    }

    public function testHasser(): void
    {
        $this->tester
            ->assertStr(Str::make('var')->hasser(), 'hasVar')
            ->assertStr(Str::make('var')->hasser('Attr'), 'hasVarAttr')
            ->assertStr(Str::make('my_var')->hasser(), 'hasMyVar')
            ->assertStr(Str::make('my_var')->hasser('Attr'), 'hasMyVarAttr');
    }

    public function testIsser(): void
    {
        $this->tester
            ->assertStr(Str::make('var')->isser(), 'isVar')
            ->assertStr(Str::make('var')->isser('Attr'), 'isVarAttr')
            ->assertStr(Str::make('my_var')->isser(), 'isMyVar')
            ->assertStr(Str::make('my_var')->isser('Attr'), 'isMyVarAttr');
    }

    public function testHasSubstr(): void
    {
        $str = Str::make('abcdef');

        $this->assertTrue($str->hasSubstr('a'));
        $this->assertFalse($str->hasSubstr('A'));

        $this->assertTrue($str->hasSubstr('a', false));
        $this->assertTrue($str->hasSubstr('A', false));

        $this->assertFalse($str->hasSubstr(' '));
        $this->assertFalse($str->hasSubstr('h'));
    }
}
