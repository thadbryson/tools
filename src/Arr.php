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

    protected static function mapInternal(array $array, array $mappings, bool $only): array
    {
        $result = $only ? [] : $array;

        foreach ($mappings as $fromDot => $toDot) {
            $result = static::move($array, $result, (string) $fromDot, (string) $toDot);
        }

        return $result;
    }

    public static function map(array $array, array $mappings): array
    {
        return static::mapInternal($array, $mappings, false);
    }

    public static function mapOnly(array $array, array $mappings): array
    {
        return static::mapInternal($array, $mappings, true);
    }

    public static function copy(array $array, array $destination, string $fromDot, string $toDot = null): array
    {
        $value = static::get($array, $fromDot);

        static::set($destination, $toDot ?? $fromDot, $value);

        return $destination;
    }

    public static function move(array &$array, array $destination, string $fromDot, string $toDot = null): array
    {
        $value = static::pull($array, $fromDot);

        static::set($destination, $toDot ?? $fromDot, $value);

        return $destination;
    }

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
}
