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
    public function testDecorator(array $items, array $values = null): void
    {
        $decorator = new Decorator($items);
        $values    = $values ?? $items;

        $this->assertEquals($values, $decorator->toArray());
        $this->assertEquals($values, $decorator->toArrayOriginal());

        foreach ($values as $property => $expected) {
            $value = $decorator->{$property};

            $this->assertEquals($expected, $value, sprintf('Property "%s", expected "%" and value "%s"',
                $property, $expected, $value));
        }
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
            'some'    => 17,
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
        $this->assertEquals(17, $decorator->some);
        $this->assertEquals([
            ['id' => 1, 'name' => 'Fred', 'age' => 11],
            ['id' => 2, 'name' => 'Chad', 'age' => 22],
            ['id' => 3, 'name' => 'John', 'age' => 33],
        ], $decorator->friends);
        $this->assertEquals(77, $decorator->age);
    }
}
