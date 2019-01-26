<?php

declare(strict_types = 1);

namespace Tests\Unit\Functions;

use function Tool\Functions\String\is_json;
use function Tool\Functions\String\is_numeric_float;
use function Tool\Functions\String\is_numeric_int;
use function Tool\Functions\String\is_timezone;
use function Tool\Functions\String\money;
use function Tool\Functions\String\money_international;

class StringFunctionsTest extends \Codeception\Test\Unit
{
    public function testIsJson(): void
    {
        $this->assertTrue(is_json('[]'));
        $this->assertTrue(is_json('{}'));
        $this->assertTrue(is_json('{"glossary":{"title":"example glossary","GlossDiv":{"title":"S","GlossList":' .
            '{"GlossEntry":{"ID":"SGML","SortAs":"SGML","GlossTerm":"Standard Generalized Markup Language",' .
            '"Acronym":"SGML","Abbrev":"ISO 8879:1986","GlossDef":{"para":"A meta-markup language, ' .
            'used to create markup languages such as DocBook.","GlossSeeAlso":["GML","XML"]},' .
            '"GlossSee":"markup"}}}}}'));

        $this->assertFalse(is_json(''));
        $this->assertFalse(is_json('~'));

        $this->assertFalse(is_json(null));
        $this->assertFalse(is_json(true));
        $this->assertFalse(is_json(false));
        $this->assertFalse(is_json(0));
        $this->assertFalse(is_json(1));
        $this->assertFalse(is_json(new \DateTime));
    }

    public function testIsTimezone(): void
    {
        $this->assertTrue(is_timezone('America/New_York'));
        $this->assertTrue(is_timezone('America/New_York '));
        $this->assertTrue(is_timezone('america/New_York'));

        $this->assertFalse(is_timezone('nope'));
        $this->assertFalse(is_timezone('America/Davie'));
    }

    public function testIsNumericInt(): void
    {
        $this->assertTrue(is_numeric_int(0));
        $this->assertTrue(is_numeric_int(1));
        $this->assertTrue(is_numeric_int(-1));
        $this->assertTrue(is_numeric_int(PHP_INT_MIN));
        $this->assertTrue(is_numeric_int(PHP_INT_MAX));

        $this->assertTrue(is_numeric_int('0'));
        $this->assertTrue(is_numeric_int('1'));
        $this->assertTrue(is_numeric_int('-1'));
        $this->assertTrue(is_numeric_int('' . PHP_INT_MIN));
        $this->assertTrue(is_numeric_int('' . PHP_INT_MAX));

        $this->assertFalse(is_numeric_int('a'));
        $this->assertFalse(is_numeric_int('0.0'));
        $this->assertFalse(is_numeric_int('1.0'));
        $this->assertFalse(is_numeric_int('-1.0'));
        $this->assertFalse(is_numeric_int('' . M_PI));
    }

    public function testIsNumericFloat(): void
    {
        $this->assertTrue(is_numeric_float(M_PI));
        $this->assertTrue(is_numeric_float(0.0));
        $this->assertTrue(is_numeric_float(1.0));
        $this->assertTrue(is_numeric_float(-1.0));

        $this->assertTrue(is_numeric_float('0.0'));
        $this->assertTrue(is_numeric_float('1.0'));
        $this->assertTrue(is_numeric_float('-1.0'));
        $this->assertTrue(is_numeric_float('' . M_PI));

        $this->assertFalse(is_numeric_float('a'));
        $this->assertFalse(is_numeric_float('1'));
        $this->assertFalse(is_numeric_float('-1'));
        $this->assertFalse(is_numeric_float('' . PHP_INT_MIN));
        $this->assertFalse(is_numeric_float('' . PHP_INT_MAX));
    }

    public function testMoney(): void
    {
        setlocale(LC_MONETARY, 'en_US');

        $this->assertEquals(money('1'), '$1.00');
        $this->assertEquals(money('0'), '$0.00');
        $this->assertEquals(money('876.53'), '$876.53');
        $this->assertEquals(money('4876.52213'), '$4,876.52');
    }

    public function testMoneyInternational(): void
    {
        $this->assertEquals(money_international('1'), 'USD 1.00');
        $this->assertEquals(money_international('0'), 'USD 0.00');
        $this->assertEquals(money_international('98345.72'), 'USD 98,345.72');
        $this->assertEquals(money_international('712.832'), 'USD 712.83');;
    }
}
