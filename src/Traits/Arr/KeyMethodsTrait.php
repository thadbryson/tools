<?php

declare(strict_types = 1);

namespace Tool\Support\Traits\Arr;

use Tool\Support\Arr;

/**
 * Class KeyMethodsTrait
 */
trait KeyMethodsTrait
{
    public static function undot(array $array, string $prepend = ''): array
    {
        $results = [];
        $prepend = trim($prepend, '.');

        foreach ($array as $dot => $value) {

            // Remove prepend (if there) from DOT key.
            if ($prepend !== '' && strpos($dot, $prepend) === 0) {
                $dot = substr($dot, strlen($prepend));
            }

            // Now trim off remaining period (if there).
            $dot = trim($dot, '.');

            Arr::set($results, $dot, $value);
        }

        return $results;
    }

    /**
     * Get all DOT keys.
     *
     * @param array $array
     * @return string[]
     */
    public static function keysDot(array $array): array
    {
        return array_keys(Arr::dot($array));
    }

    /**
     * @param array  $array
     * @param string ...$dots
     *
     * @return array
     */
    public static function orderKeys(array $array, string ...$dots): array
    {
        $array  = Arr::dot($array);
        $sorted = [];

        foreach ($dots as $dot) {
            // Get value from array and remove it.
            // (remove to save memory in case of large arrays)
            $sorted[$dot] = Arr::pull($array, $dot);
        }

        // Undo the dot keys.
        $sorted = Arr::undot($sorted);

        // Now put whatever is left on the $array on the end.
        return array_merge($sorted, $array);
    }

    /**
     * Rename keys from (current DOT key => new DOT key)
     *
     * @param array $mappings
     *
     * @return array
     */
    public static function renameKeys(array $array, array $mappings): array
    {
        foreach ($mappings as $fromDot => $toDot) {
            $value = Arr::pull($array, $fromDot);

            Arr::set($array, $toDot, $value);
        }

        return $array;
    }

    /**
     * Determines if an array is NOT associative.
     *
     * An array is "associative" if it doesn't have sequential numerical keys beginning with zero.
     *
     * @param  array $array
     * @return bool
     */
    public static function isNotAssoc(array $array): bool
    {
        return Arr::isAssoc($array) === false;
    }
}
