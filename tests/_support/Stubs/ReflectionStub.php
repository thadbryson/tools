<?php

declare(strict_types = 1);

namespace Tests\Support\Stubs;

/**
 * Class ReflectionStub
 */
final class ReflectionStub
{
    public $prop = 1;

    public function method(string $arg): int
    {
        return 10 + (int) $arg;
    }
}