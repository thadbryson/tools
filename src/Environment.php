<?php

declare(strict_types = 1);

namespace Tool;

use function php_sapi_name;
use const PHP_OS;

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

    protected static function getOperatingSystem(): string
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
        return static::getOperatingSystem() === 'WIN';
    }

    /**
     * Are we on a Linux Operating system?
     *
     * @return bool
     */
    public static function isLinux(): bool
    {
        return static::getOperatingSystem() === 'LIN';
    }

    /**
     * Are we on a Macintosh or Free BSD Operating system? Could be a Mac too.
     *
     * @return bool
     */
    public static function isMac(): bool
    {
        return static::getOperatingSystem() === 'FRE';
    }

    /**
     * Is the current Request coming from a mobile device?
     * From: http://detectmobilebrowsers.com/
     */
    public static function isMobile(): bool
    {
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

        if ($agent === '') {
            return false;
        }

        return Request::isAgentMobile($agent);
    }
}