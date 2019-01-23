<?php

declare(strict_types = 1);

namespace tool\functions\string;

use Tool\Validation\Assert;
use function is_float;
use function is_int;
use function is_numeric;
use function is_string;
use function strpos;

/**
 * Is variable given a valid DateTimeZone string?
 *
 * @param mixed $var
 *
 * @return bool
 */
function is_timezone($var): bool
{
    try {

        if (is_string($var) === false) {
            return false;
        }

        new \DateTimeZone($var);

        return true;
    } catch (\Exception $e) {
        return false;
    }
}

/**
 * Is this a numeric string of an integer?
 */
function is_numeric_int($var): bool
{
    if (is_int($var)) {
        return true;
    }

    return is_string($var) && is_numeric($var) && strpos($var, '.') === false;
}

/**
 * Is this a numberic string of a float?
 */
function is_numeric_float($var): bool
{
    if (is_float($var)) {
        return true;
    }

    return is_string($var) && is_numeric($var) && strpos($var, '.') !== false;
}

/**
 * Get monetery string format.
 *
 * @param string|int|float $str
 *
 * @return string
 */
function money($var, string $locale = 'en_US', string $encoding = 'UTF-8'): string
{
    Assert::numeric($var, '$var must be a numeric string, integer, or float.');

    setlocale(LC_MONETARY, $locale . '.' . $encoding);

    return money_format('%n', (float) $var);
}

/**
 * Get international monetery format.
 *
 * @param string|int|float $str
 *
 * @return string
 */
function money_international($var, string $locale = 'en_US', string $encoding = 'UTF-8'): string
{
    Assert::numeric($var, '$var must be a numeric string, integer, or float.');

    setlocale(LC_MONETARY, $locale . '.' . $encoding);

    return money_format('%i', (float) $var);
}
