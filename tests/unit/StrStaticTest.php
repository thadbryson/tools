<?php

declare(strict_types = 1);

namespace Tests\Unit;

use Tool\StrStatic;
use UnitTester;

/**
 * Class StrStaticTest
 */
class StrStaticTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public function testCallStatic(): void
    {
        $this->assertEquals(['test', 'comma', ' string'], StrStatic::explode('test,comma, string', ','));
        $this->assertEquals('', StrStatic::beforeSubstr('substring', 'sub'));
        $this->assertEquals('apples', StrStatic::plural('apple', 0));
        $this->assertEquals('orange', StrStatic::plural('orange', 1));
        $this->assertEquals('bananas', StrStatic::plural('banana', 2));
        $this->assertEquals('grapes', StrStatic::plural('grapes', 10));
        $this->assertEquals('getId', StrStatic::getter('id'));
        $this->assertEquals('setNameAttribute', StrStatic::setter('Name', 'Attribute'));
    }
}
