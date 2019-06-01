<?php

declare(strict_types = 1);

namespace Tests\Unit;

use Tests\Support\Stubs\ExtendedDecorator;
use Tool\Decorator;
use UnitTester;

class DecoratorTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    /**
     * @dataProvider dataDecorator
     */
    private function assertDecorator(Decorator $decorator, array $items, array $values = null): void
    {
        $values = $values ?? $items;

        $this->assertEquals($values, $decorator->toArray());
        $this->assertEquals($values, $decorator->toArrayOriginal());

        foreach ($values as $property => $expected) {
            $value = $decorator->{$property};

            $this->assertEquals($expected, $value, sprintf('Property "%s", expected "%" and value "%s"',
                $property, $expected, $value));
        }
    }

    /**
     * @dataProvider dataDecorator
     */
    public function testDecorator(array $items, array $values = null): void
    {
        $this->assertDecorator(new Decorator($items), $items, $values);
    }

    /**
     * @dataProvider dataDecorator
     */
    public function testMakeDecorator(array $items, array $values = null): void
    {
        $this->assertDecorator(Decorator::make($items), $items, $values);
    }

    public function dataDecorator(): array
    {
        return [
            [[]],
            [
                [
                    'id'   => 1,
                    'name' => 'Tester Bob',
                    'age'  => 33,
                    'dob'  => 'July 4',
                ],
            ],
        ];
    }

    public function testDecoratorExtendedFullFeatures(): void
    {
        $decorator = new ExtendedDecorator(ExtendedDecorator::TEST_DATA);

        $this->assertEquals(ExtendedDecorator::TEST_DATA, $decorator->toArrayOriginal());

        $expected = [
            'id'      => 'id: 5',
            'me'      => 'Who?',
            'some'    => 27,
            'friends' => [
                ['id' => 1, 'name' => 'Fred', 'age' => 11],
                ['id' => 2, 'name' => 'Chad', 'age' => 22],
                ['id' => 3, 'name' => 'John', 'age' => 33],
            ],
            'age'     => 77,
        ];

        $this->assertEquals($expected, $decorator->toArray());

        $this->assertEquals('id: 5', $decorator->id);
        $this->assertEquals('Who?', $decorator->me);
        $this->assertEquals(27, $decorator->some);
        $this->assertEquals([
            ['id' => 1, 'name' => 'Fred', 'age' => 11],
            ['id' => 2, 'name' => 'Chad', 'age' => 22],
            ['id' => 3, 'name' => 'John', 'age' => 33],
        ], $decorator->friends);
        $this->assertEquals(77, $decorator->age);
    }
}
