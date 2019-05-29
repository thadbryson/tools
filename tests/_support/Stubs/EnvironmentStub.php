<?php

declare(strict_types = 1);

namespace Tests\Support\Stubs;

use Tool\Environment;

class EnvironmentStub extends Environment
{
    protected static $os;

    protected static function getOperatingSystem(): string
    {
        return static::$os;
    }

    public static function setOperatingSystem(string $os): void
    {
        static::$os = $os;
    }
}