<?php

declare(strict_types = 1);

namespace Tool\Support;

use ArrayAccess;
use InvalidArgumentException;
use Tool\Validation\Assert;
use function array_merge;
use function array_merge_recursive;
use function array_replace_recursive;
use function is_object;
use function method_exists;
use function preg_match;

/**
 * Array helper class.
 */
class Arr extends \Illuminate\Support\Arr
{
    /**
     * @param \ArrayAccess|array $array
     * @param array              $mappings
     *
     * @return \ArrayAccess|array
     */
    public static function map($array, array $mappings)
    {
        $mapped = [];

        foreach ($mappings as $fromDot => $toDot) {
            static::copy($array, $mapped, $fromDot, $toDot);
        }

        return $mapped;
    }

    /**
     * @param \ArrayAccess|array $array
     * @param \ArrayAccess|array $destination
     * @param string             $fromDot
     * @param string             $toDot
     *
     * @return void
     */
    public static function copy($array, &$destination, string $fromDot, string $toDot): void
    {
        $destination = Assert::isArrayAccessible($destination ?? [],
            '$destination must be an array, an ArrayAccess object, or NULL');

        $value = static::get($array, $fromDot);

        static::set($destination, $toDot, $value);
    }

    /**
     * Convert DOT keys into normal key array.
     */
    public static function undot(array $array): array
    {
        $results = [];

        foreach ($array as $dot => $value) {
            static::set($results, $dot, $value);
        }

        return $results;
    }

    /**
     * Order array keys exactly as specified
     */
    public static function orderKeys(array $array, string ...$dots): array
    {
        $array  = static::dot($array);
        $sorted = [];

        foreach ($dots as $dot) {
            // Get value from array and remove it.
            // (remove to save memory in case of large arrays)
            $sorted[$dot] = static::pull($array, $dot);
        }

        // Undo the dot keys.
        $sorted = static::undot($sorted);

        // Now put whatever is left on the $array on the end.
        return array_merge($sorted, $array);
    }

    public static function merge(array $array1, array $array2, array ...$arrays): array
    {
        $arrays = static::prepend($arrays, $array2, $array1);

        return array_merge_recursive(...$arrays);
    }

    public static function replace(array $array1, array $array2, array ...$arrays): array
    {
        $arrays = static::prepend($arrays, $array2, $array1);

        return array_replace_recursive(...$arrays);
    }

    /**
     * Convert all these to an array.
     *
     * @param mixed $array
     *
     * @return array
     * @throws InvalidArgumentException
     */
    public static function cast(&$array): array
    {
        // Object with ->toArray() method?
        if (is_object($array) && method_exists($array, 'toArray')) {
            return $array->toArray();
        }

        // An Iterator object?
        if ($array instanceof \iterable) {
            return iterator_to_array($array, true);
        }

        // ArrayAccess object?
        if ($array instanceof ArrayAccess) {
            return (array) $array;
        }

        // Just put it in an array. Or return itself it's already an array.
        return parent::wrap($array);
    }

    /**
     * @param array $array
     */
    public static function remove(array $array, string ...$dots): array
    {
        static::forget($array, $dots);

        return $array;
    }

    /**
     * Check if an item or items exist in an array using "dot" notation.
     *
     * @param  array $array
     */
    public static function hasAll(array $array, string ...$dots): bool
    {
        return parent::has($array, $dots);
    }

    /**
     * Get a subset of the items from the given array.
     *
     * @param  array $array
     */
    public static function getAll(array $array, string ...$dots): array
    {
        return parent::only($array, $dots);
    }

    /**
     * Determine if all arrays are empty,
     *
     * @param array ...$arrays
     */
    public static function isEmpty(array ...$arrays): bool
    {
        foreach ($arrays as $array) {
            if (empty($array) === false) {
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
        return !static::isEmpty(...$arrays);
    }

    public static function whereValue(array $array, $value): array
    {
        return static::where($array, function ($item) use ($value) {

            return $item === $value;
        });
    }

    public static function whereValueStrict(array $array, $value): array
    {
        return static::where($array, function ($item) use ($value) {

            return $item === $value;
        });
    }

    /**
     * Filter items matching Regular expression given.
     *
     * @param array|ArrayAccess $array
     */
    public static function whereRegex(array $array, string $key, string $regex): array
    {
        return static::where($array, static::operandRegexWhere($key, $regex, 1));
    }

    /**
     * Filter items matching Regular expression given.
     *
     * @param array|ArrayAccess $array
     */
    public static function whereNotRegex(array $array, string $key, string $regex): array
    {
        return static::where($array, static::operandRegexWhere($key, $regex, 0));
    }

    /**
     * Filter items matching Regular expression given.
     *
     * @param array|ArrayAccess $array
     */
    public static function whereLike(array $array, string $key, string $like): array
    {
        $regex = '/' . str_replace('%', '.+', $like) . '/m';

        return static::whereRegex($array, $key, $regex);
    }

    /**
     * Filter items matching Regular expression given.
     *
     * @param array|ArrayAccess $array
     */
    public static function whereNotLike(array $array, string $key, string $like): array
    {
        $regex = '/' . str_replace('%', '.+', $like) . '/m';

        return static::whereNotRegex($array, $key, $regex);
    }

    /**
     * Get an operator checker callback.
     */
    protected static function operatorForWhere(string $key, string $operator, $value): \Closure
    {
        return function ($item) use ($key, $operator, $value) {
            $retrieved = data_get($item, $key);

            try {
                switch (trim($operator)) {
                    case '=':
                    case '===':
                        return $retrieved === $value;
                    case '==':
                        return $retrieved == $value;
                    case '!=':
                    case '<>':
                        return $retrieved != $value;
                    case '<':
                        return $retrieved < $value;
                    case '>':
                        return $retrieved > $value;
                    case '<=':
                        return $retrieved <= $value;
                    case '>=':
                        return $retrieved >= $value;
                    case '!==':
                        return $retrieved !== $value;
                    default:
                }
            } catch (\Exception $_) {

            }

            return false;
        };
    }

    /**
     * Get callable for comparing Regular expressions in where methods.
     */
    protected static function operandRegexWhere(string $key, string $regex, int $found): \Closure
    {
        return function ($item) use ($key, $regex, $found) {

            $retrieved = data_get($item, $key);

            return preg_match($regex, $retrieved) === $found;
        };
    }
}
