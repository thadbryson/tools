<?php

declare(strict_types = 1);

namespace Tool;

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
}