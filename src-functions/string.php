<?php

declare(strict_types=1);

namespace Tool\Functions\String;

use Tool\Validation\Assert;
use function is_float;
use function is_numeric;
use function is_string;
use function json_decode;
use function json_last_error;
use function strpos;
use const JSON_ERROR_NONE;

/**
 * Is this a string?
 */
function is_json($var): bool
{
    if (is_string($var)) {
        json_decode($var);

        return json_last_error() === JSON_ERROR_NONE;
    }

    return false;
}

/**
 * Is this a timezone?
 */
function is_timezone(string $var): bool
{
    try {
        new \DateTimeZone($var);

        return true;
    } catch (\Exception $e) {
        return false;
    }
}

/**
 * Is this a numeric string or an integer?
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
 * Get money format of numeric $var.
 */
function money(string $var, string $locale = 'en_US', string $encoding = 'UTF-8'): string
{
    Assert::numeric($var, '$var must be a numeric string, integer, or float.');

    setlocale(LC_MONETARY, $locale . '.' . $encoding);

    return money_format('%n', (float)$var);
}

/**
 * Get international money format of numeric $var.
 */
function money_international(string $var, string $locale = 'en_US', string $encoding = 'UTF-8'): string
{
    Assert::numeric($var, '$var must be a numeric string, integer, or float.');

    setlocale(LC_MONETARY, $locale . '.' . $encoding);

    return money_format('%i', (float)$var);
}
