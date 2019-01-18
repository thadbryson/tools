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
     * @alias forget(array $array, $keys)
     */
    public static function remove(array $array, string ...$dots): array
    {
        return Arr::except($array, $dots);
    }

    /**
     * @alias forget(array $array, $keys)
     */
    public static function blacklist(array $array, string ...$dots): array
    {
        return Arr::except($array, $dots);
    }

    /**
     * @alias only(array $array, array $keys)
     */
    public static function whitelist(array $array, string ...$dots): array
    {
        return Arr::only($array, $dots);
    }

    /**
     * @param array $array
     *
     * @return mixed
     */
    public static function removeFirst(array $array)
    {
        return array_shift($array);
    }

    /**
     * @param array $array
     *
     * @return mixed
     */
    public static function removeLast(array $array)
    {
        return array_pop($array);
    }
}
