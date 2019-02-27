<?php

declare(strict_types = 1);

namespace Tool;

use const PHP_OS;
use function php_sapi_name;

/**
 * Class Environment
 *
 * Helper class to help with the environment.
 */
class Environment
{
    /**
     * Are we currently using the command line?
     *
     * @return bool
     */
    public static function isCommandLine(): bool
    {
        return php_sapi_name() === 'cli';
    }

    protected static function getOS(): string
    {
        return Str::make(PHP_OS)
            ->substr(0, 3)
            ->toUpperCase()
            ->get();
    }

    /**
     * Are we on a Windows Operating system?
     *
     * @return bool
     */
    public static function isWindows(): bool
    {
        return static::getOS() === 'WIN';
    }

    /**
     * Are we on a Linux Operating system?
     *
     * @return bool
     */
    public static function isLinux(): bool
    {
        return static::getOS() === 'LIN';
    }

    /**
     * Are we on a Free BSD Operating system? Could be a Mac too.
     *
     * @return bool
     */
    public static function isFreeBSD(): bool
    {
        return static::getOS() === 'FRE';
    }
}