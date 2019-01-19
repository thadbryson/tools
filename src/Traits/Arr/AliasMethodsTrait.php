<?php

declare(strict_types = 1);

namespace Tool\Support\Traits\Arr;

use Tool\Support\Arr;
use function array_pop;
use function array_shift;

/**
 * Class AliasMethodsTrait
 */
trait AliasMethodsTrait
{
    /**
     * Remove DOT keys from $array. Return new $array.
     *
     * @param array  $array
     * @param string ...$dots
     *
     * @return array
     */
    public static function remove(array $array, string ...$dots): array
    {
        return Arr::except($array, $dots);
    }

    /**
     * Remove first value from the array.
     *
     * @param array &$array
     *
     * @return mixed
     */
    public static function removeFirst(array &$array)
    {
        return array_shift($array);
    }

    /**
     * Remove last value in the array.
     *
     * @param array &$array
     *
     * @return mixed
     */
    public static function removeLast(array &$array)
    {
        return array_pop($array);
    }
}
