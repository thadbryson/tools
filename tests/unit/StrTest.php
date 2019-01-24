<?php

declare(strict_types = 1);

namespace Tests\Unit;

use Tool\Str;
use UnitTester;
use function json_encode;
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

    public function testExplode(): void
    {
        $str = Str::make('test,comma, string');

        $this->assertEquals(['test', 'comma', ' string'], $str->explode(','), '3 elements.');

        $this->assertEquals(['test,comma, string'], $str->explode(':'), 'Delimiter not in string.');
        $this->assertEquals(['test,comma,', 'string'], $str->explode(' '), 'Delimiter not in string.');
    }

    public function testGet(): void
    {
        $str = Str::make(' what??? ');

        $this->assertEquals(' what??? ', $str->get());
        $this->assertEquals(' what??? ', $str->__toString());
        $this->assertEquals(' what??? ', (string) $str);
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

    public function testMoney(): void
    {
        setlocale(LC_MONETARY, 'en_US');

        $this->tester
            ->assertStr(Str::make('1')->money(), '$1.00')
            ->assertStr(Str::make('0')->money(), '$0.00')
            ->assertStr(Str::make('876.53')->money(), '$876.53')
            ->assertStr(Str::make('4876.52213')->money(), '$4,876.52');
    }

    public function testMoneyInternational(): void
    {
        $this->tester
            ->assertStr(Str::make('1')->moneyInternational(), 'USD 1.00')
            ->assertStr(Str::make('0')->moneyInternational(), 'USD 0.00')
            ->assertStr(Str::make('98345.72')->moneyInternational(), 'USD 98,345.72')
            ->assertStr(Str::make('712.832')->moneyInternational(), 'USD 712.83');
    }
}
