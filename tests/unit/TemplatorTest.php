<?php

declare(strict_types = 1);

namespace Tests\Unit;

use Tool\Templator;

class TemplatorTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected $engine;

    public function _before(): void
    {
        $loader = new \Twig_Loader_Array([
            'template' => 'some',
        ]);

        $this->engine = new \Twig_Environment($loader);
    }

    public function testGetEngin(): void
    {
        $this->assertInstanceOf(\Twig_Loader_Array::class, Templator::getEngine()->getLoader());
    }

    public function testSetEngin(): void
    {
        Templator::setEngine($this->engine);

        $this->assertTrue(Templator::getEngine()->getLoader()->exists('template'));
    }

    public function testClear(): void
    {
        Templator::setEngine($this->engine);
        $this->assertTrue(Templator::getEngine()->getLoader()->exists('template'));

        Templator::clearEngine();
        $this->assertFalse(Templator::getEngine()->getLoader()->exists('template'));
    }

    public function testMake(): void
    {
        $this->assertEquals('Some good person.', Templator::make('Some {{ type }} person.', [
            'type' => 'good',
        ]));

        $this->assertEquals('Some GOOD person.', Templator::make('Some {{ type|upper }} person.', [
            'type' => 'good',
        ]));
    }

    public function testFromFile(): void
    {
        $output = Templator::fromFile(__DIR__ . '/../_support/Stubs/template_file.twig', [
            'type'   => 'GOOD',
            'quote'  => 'Life is good.',
            'author' => 'me',
        ]);

        $expected = <<<EOT
A really good person once said:

"Life is good."

- ME
EOT;

        $this->assertEquals($expected, $output);
    }
}
