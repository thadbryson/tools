<?php

declare(strict_types = 1);

namespace Tool\Traits\Arr;

use Tool\Arr;

/**
 * Class MapTrait
 *
 * @mixin Arr
 */
trait MapTrait
{
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
