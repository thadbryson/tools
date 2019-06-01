<?php

declare(strict_types = 1);

namespace Tests\Support\Stubs;

use Tool\Decorator;

/**
 * Class ExtendedDecorator
 *
 * @property-read string $id
 * @property-read string $me
 * @property-read int    $some
 * @property-read array  $friends
 * @property-read int    $age
 */
class ExtendedDecorator extends Decorator
{
    public const TEST_DATA = [
        'id'      => 5,
        'me'      => 'Who?',
        'some'    => 7,
        'friends' => [
            ['id' => 1, 'name' => 'Fred', 'age' => 11],
            ['id' => 2, 'name' => 'Chad', 'age' => 22],
            ['id' => 3, 'name' => 'John', 'age' => 33],
        ],
        'age'     => 77,
    ];

    protected $defaults = self::TEST_DATA;

    public function getId(): string
    {
        return 'id: ' . static::TEST_DATA['id'];
    }

    public function getSome(int $value): int
    {
        return $value + 10;
    }
}
