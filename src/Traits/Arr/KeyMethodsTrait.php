<?php

declare(strict_types = 1);

namespace Tool\Traits\Arr;

use Tool\Arr;

/**
 * Class KeyMethodsTrait
 */
trait KeyMethodsTrait
{
    /**
     * Turn DOT keyed array into a normal array.
     *
     * @param array  $array
     * @param string $prepend
     *
     * @return array
     */
    public static function undot(array $array, string $prepend = ''): array
    {
        $results = [];
        $prepend = trim($prepend, '.');

        foreach ($array as $dot => $value) {

            $dot = (string) $dot;

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
