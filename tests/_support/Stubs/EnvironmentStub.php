<?php

declare(strict_types = 1);

namespace Tests\Support\Stubs;

use Tool\Environment;

class EnvironmentStub extends Environment
{
    protected static $os;

    protected static function getOS(): string
    {
        return static::$os;
    }

    public static function setOs(string $os): void
    {
        static::$os = $os;
    }
}