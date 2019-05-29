<?php

declare(strict_types = 1);

namespace Tool\Traits\Arr;

use Tool\Arr;
use Tool\Validation\Assert;

/**
 * Trait InTrait
 *
 * @mixin Arr
 */
trait InTrait
{
    /**
     * Do all these values exist in the $array (1-dimension). Strict === comparison.
     *
     * @param array $array
     * @param mixed ...$values
     * @return bool
     */
    public static function in(array $array, ...$values): bool
    {
        Assert::minCount($values, 1, 'Haystack array cannot be empty.');

        foreach ($values as $value) {

            if (in_array($value, $array, true) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Do all these values exist in the $array (1-dimension). Strict === comparison.
     *
     * @param array $array
     * @param mixed ...$values
     * @return bool
     */
    public static function inAny(array $array, ...$values): bool
    {
        Assert::minCount($values, 1, 'Haystack array cannot be empty.');

        foreach ($values as $value) {

            if (in_array($value, $array, true) === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Do all these values exist in the $array (1-dimension), non-strict == comparison.
     *
     * @param array $array
     * @param mixed ...$values
     * @return bool
     */
    public static function inLoose(array $array, ...$values): bool
    {
        Assert::minCount($values, 1, 'Haystack array cannot be empty.');

        foreach ($values as $value) {

            if (in_array($value, $array, false) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Do all these values exist in the $array (1-dimension), non-strict == comparison.
     *
     * @param array $array
     * @param mixed ...$values
     * @return bool
     */
    public static function inLooseAny(array $array, ...$values): bool
    {
        Assert::minCount($values, 1, 'Haystack array cannot be empty.');

        foreach ($values as $value) {

            if (in_array($value, $array, false) === true) {
                return true;
            }
        }

        return false;
    }
}
