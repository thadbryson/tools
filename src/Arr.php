<?php

declare(strict_types = 1);

namespace Tool\Support;

use Tool\Support\Traits\Arr as ArrTraits;
use function array_replace_recursive;
use function array_walk_recursive;
use function is_string;

/**
 * Array helper class.
 */
class Arr extends \Illuminate\Support\Arr
{
    use ArrTraits\AliasMethodsTrait,
        ArrTraits\KeyMethodsTrait;

    /**
     * Handles map() and mapOnly() methods internally.
     *
     * @param array    $array
     * @param string[] $mappings
     * @param bool     $only
     *
     * @return array
     */
    protected static function mapInternal(array $array, array $mappings, bool $only): array
    {
        $result = $only ? [] : $array;

        foreach ($mappings as $fromDot => $toDot) {
            $result = static::move($array, $result, (string) $fromDot, (string) $toDot);
        }

        return $result;
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
     * Copy a value from $array to $destination.
     *
     * @param array       $array
     * @param array       $destination
     * @param string      $fromDot - DOT key in $array to copy
     * @param string|null $toDot   - DOT key to copy to, if NULL will use same DOT as $fromDOT
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
     * Move a value from $array to $destination.
     *
     * @param array       &$array
     * @param array        $destination
     * @param string       $fromDot - DOT key in $array to copy
     * @param string|null  $toDot   - DOT key to copy to, if NULL will use same DOT as $fromDOT
     *
     * @return array
     */
    public static function move(array &$array, array $destination, string $fromDot, string $toDot = null): array
    {
        $value = static::pull($array, $fromDot);

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
     * Determine if all arrays are not empty.
     *
     * @param array ...$arrays
     */
    public static function isNotEmpty(array ...$arrays): bool
    {
        return static::isEmpty(...$arrays) === false;
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
}
