<?php

declare(strict_types = 1);

namespace Tool;

use Tool\Traits\Arr as ArrTraits;
use Tool\Validation\Assert;
use function array_replace_recursive;
use function array_walk_recursive;
use function in_array;
use function is_string;

/**
 * Array helper class.
 */
class Arr extends \Illuminate\Support\Arr
{
    use ArrTraits\AliasMethodsTrait,
        ArrTraits\KeyMethodsTrait;

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

    /**
     * Change keys / values in $mappings (from DOT => to DOT).
     *
     * @param array    $array
     * @param string[] $mappings
     *
     * @return array
     */
    public static function map(array $array, array $mappings): array
    {
        return static::mapInternal($array, $mappings, false);
    }

    /**
     * Change keys / values in $mappings (from DOT => to DOT).
     *
     * @param array    $arrays
     * @param string[] $mappings
     * @param string   $keyMap = null
     *
     * @return array
     */
    public static function mapEach(array $arrays, array $mappings, string $keyMap = null): array
    {
        $return = [];

        foreach ($arrays as $index => $array) {

            $return[$index] = static::mapInternal($array, $mappings, false);

            if ($keyMap !== null) {
                $return[$index][$keyMap] = $index;
            }
        }

        return $return;
    }

    /**
     * Move a value from $array to $destination.
     *
     * @param array       &$array
     * @param array        $destination
     * @param string       $fromDot - DOT key in $array to copy
     * @param string|null  $toDot - DOT key to copy to, if NULL will use same DOT as $fromDOT
     *
     * @return array
     */
    public static function move(array &$array, array $destination, string $fromDot, string $toDot = null): array
    {
        $value = static::pull($array, $fromDot);
        $toDot = $toDot ?? $fromDot;

        return static::set($destination, $toDot, $value);
    }

    /**
     * Map $array with $mappings ($fromDOT => $toDOT). Leave only values mapped in new array.
     *
     * @param array    $array
     * @param string[] $mappings
     *
     * @return array
     */
    public static function mapOnly(array $array, array $mappings): array
    {
        return static::mapInternal($array, $mappings, true);
    }

    /**
     * Change keys / values in $mappings (from DOT => to DOT).
     *
     * @param array    $arrays
     * @param string[] $mappings
     * @param string   $keyMap = null
     *
     * @return array
     */
    public static function mapEachOnly(array $arrays, array $mappings, string $keyMap = null): array
    {
        $return = [];

        foreach ($arrays as $index => $array) {

            $return[$index] = static::mapInternal($array, $mappings, true);

            if ($keyMap !== null) {
                $return[$index][$keyMap] = $index;
            }
        }

        return $return;
    }

    /**
     * Copy a value from $array to $destination.
     *
     * @param array       $array
     * @param array       $destination
     * @param string      $fromDot - DOT key in $array to copy
     * @param string|null $toDot - DOT key to copy to, if NULL will use same DOT as $fromDOT
     *
     * @return array
     */
    public static function copy(array $array, array $destination, string $fromDot, string $toDot = null): array
    {
        $value = static::get($array, $fromDot);

        static::set($destination, $toDot ?? $fromDot, $value);

        return $destination;
    }

    /**
     * Run trim() on all string values in the array.
     *
     * @param array  $array
     * @param string $chars
     *
     * @return array
     */
    public static function trimAll(array $array, string $chars = " \t\n\r\0\x0B"): array
    {
        array_walk_recursive($array, function (&$value) use ($chars) {

            if (is_string($value) === true) {
                $value = trim($value, $chars);
            }
        });

        return $array;
    }

    /**
     * Set any default values (on $defaults) on $array.
     *
     * @param array $array
     * @param array $defaults
     *
     * @return array
     */
    public static function defaults(array $array, array $defaults): array
    {
        $defaults = static::undot($defaults);

        return array_replace_recursive($defaults, $array);
    }

    /**
     * Get a subset of the items from the given array.
     *
     * @param array  $array
     * @param string ...$dots
     *
     * @return array
     */
    public static function getMany(array $array, string ...$dots): array
    {
        $return = [];

        foreach ($dots as $dot) {
            $return[$dot] = static::get($array, $dot);
        }

        return $return;
    }

    /**
     * Get a subset of the items from the given array.
     *
     * @param array  $array
     * @param array  $dotsValues
     * @param string $prepend = ''
     *
     * @return array
     */
    public static function setMany(array $array, array $dotsValues, string $prepend = ''): array
    {
        $dotsValues = static::dot($dotsValues, $prepend);

        foreach ($dotsValues as $key => $value) {
            static::set($array, $key, $value);
        }

        return $array;
    }

    /**
     * Determine if all arrays are not empty.
     *
     * @param array ...$arrays
     */
    public static function isNotEmpty(array ...$arrays): bool
    {
        return static::isEmpty(...$arrays) === false;
    }

    /**
     * Determine if all arrays are empty,
     *
     * @param array ...$arrays
     */
    public static function isEmpty(array ...$arrays): bool
    {
        foreach ($arrays as $array) {

            if ($array !== []) {
                return false;
            }
        }

        return true;
    }

    /**
     * If any DOT keys are found in the $array, throw an \InvalidArgumentException
     *
     * @param array  $array
     * @param string ...$dots
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public static function blacklist(array $array, string ...$dots): array
    {
        if (static::except($array, $dots) !== $array) {
            throw new \InvalidArgumentException('Inivalid key/value pairs found in array.');
        }

        return $array;
    }

    /**
     * If any other keys are found in the $array besides DOT keys given, throw an \InvalidArgumentException
     *
     * @param array  $array
     * @param string ...$dots
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public static function whitelist(array $array, string ...$dots): array
    {
        if (static::only($array, $dots) !== $array) {
            throw new \InvalidArgumentException('Inivalid key/value pairs found in array.');
        }

        return $array;
    }

    /**
     * Handles map() and mapOnly() methods internally.
     *
     * @param array    $array
     * @param string[] $mappings
     * @param bool     $only
     *
     * @return array
     */
    protected static function mapInternal($array, array $mappings, bool $only): array
    {
        $result = $only ? [] : $array;

        foreach ($mappings as $fromDot => $toDot) {
            $result = static::move($array, $result, (string) $fromDot, $toDot);
        }

        return $result;
    }
}
