<?php

declare(strict_types = 1);

namespace tool\support;

use function is_int;
use function is_numeric;
use function is_string;

function is_timezone($str): bool
{
    if (is_string($str) === false) {
        return false;
    }

    try {
        new \DateTimeZone($str);

        return true;
    }
    catch (\Exception $e) {
        return false;
    }
}

/**
 * Is this a numeric string of an integer?
 */
function is_numeric_int($str): bool
{
    if (is_int($str)) {
        return true;
    }

    if (is_string($str) && is_numeric($str) && strpos($str, '.') === false) {
        return true;
    }

    return false;
}

/**
 * Is this a numberic string of a float?
 */
function is_numeric_float($str): bool
{
    return is_numeric($str) && strpos($str, '.') > 0;
}

/**
 * Get monetery string format.
 */
function money($str): string
{
    return money_format('%.2n', $str);
}

/**
 * Get international monetery format.
 */
function money_international($str): string
{
    return money_format('%.2i', $str);
}

/**
 * Get display format of phone number.
 *
 * @see Phone::display($str, $locale);
 */
function phone($str, string $locale = null): string
{
    return Phone::make($str, $locale)->display();
}

/**
 * Phone International number format.
 *
 * @see Phone::plain($str, $locale);
 */
function phone_plain($str, string $locale = null): string
{
    return Phone::make($str, $locale)->plain();
}
