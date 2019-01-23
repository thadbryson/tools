<?php

declare(strict_types = 1);

namespace Tests\Unit;

use Tool\Support\Templator;

class TemplatorTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected $twig;

    public function _before(): void
    {
        $loader = new \Twig_Loader_Array([
            'template' => 'some'
        ]);

        $this->twig = new \Twig_Environment($loader);
    }

    public function testGetTwig(): void
    {
        $this->assertInstanceOf(\Twig_Loader_Array::class, Templator::getTwig()->getLoader());
    }

    public function testSetTwig(): void
    {
        Templator::setTwig($this->twig);

        $this->assertTrue(Templator::getTwig()->getLoader()->exists('template'));
    }

    public function testClearTwig(): void
    {
        Templator::setTwig($this->twig);
        $this->assertTrue(Templator::getTwig()->getLoader()->exists('template'));

        Templator::clearTwig();
        $this->assertFalse(Templator::getTwig()->getLoader()->exists('template'));
    }

    public function testMake(): void
    {
        $this->assertEquals('Some good person.', Templator::make('Some {{ type }} person.', [
            'type' => 'good'
        ]));

        $this->assertEquals('Some GOOD person.', Templator::make('Some {{ type|upper }} person.', [
            'type' => 'good'
        ]));
    }

    public function testFromFile(): void
    {
        $output = Templator::fromFile(__DIR__ . '/../_support/Stubs/template_file.twig', [
            'type'   => 'GOOD',
            'quote'  => 'Life is good.',
            'author' => 'me'
        ]);

        $expected = <<<EOT
A really good person once said:

"Life is good."

- ME
EOT;

        $this->assertEquals($expected, $output);
    }
}
