<?php

declare(strict_types = 1);

namespace Tool;

use InvalidArgumentException;
use function array_replace_recursive;
use function is_float;
use function is_int;
use function is_string;
use function strtolower;

/**
 * Cast Class
 */
class Cast
{
    /**
     * Cast all values given.
     *
     * @param array $values
     * @param array $casts
     *
     * @return array
     */
    public static function all(array $values, array $casts): array
    {
        $casted = [];

        foreach ($casts as $dot => $type) {

            $value = Arr::get($values, $dot);

            // Set new casted value.
            $value = static::cast($value, $type);

            Arr::set($casted, $dot, $value);
        }

        return array_replace_recursive($values, $casted);
    }

    /**
     * @param mixed  $value
     * @param string $type
     *
     * @return null|bool|int|float|string|array|Clock|Collection
     * @throws InvalidArgumentException
     */
    public static function cast($value, string $type)
    {
        if ($value === null || $type === '') {
            return null;
        }

        $type = strtolower(trim($type));

        switch ($type) {

            case 'bool':
                return static::toBoolean($value);

            case 'int':
                return static::toInteger($value);

            case 'float':
                return static::toFloat($value);

            case 'string':
                return static::toString($value);

            case 'array':
                return static::toArray($value);

            default:
                throw new InvalidArgumentException('Invalid cast type given: ' . $type);
        }
    }

    public static function toBoolean($value): ?bool
    {
        if ($value === true || $value === 1 || $value === '1') {
            return true;
        }

        if ($value === false || $value === 0 || $value === '0') {
            return false;
        }

        return null;
    }

    public static function toInteger($value): ?int
    {
        if (is_int($value)) {
            return $value;
        }

        /** @noinspection TypeUnsafeComparisonInspection */
        if (is_string($value) && (int) $value == (float) $value) {
            return (int) $value;
        }

        return null;
    }

    public static function toFloat($value): ?float
    {
        if (is_float($value) || is_int($value)) {
            return (float) $value;
        }

        /** @noinspection TypeUnsafeComparisonInspection */
        if (is_string($value) && (float) $value == $value) {
            return (float) $value;
        }

        return null;
    }

    public static function toString($value): ?string
    {
        if (is_string($value) || is_int($value) || is_float($value)) {
            return (string) $value;
        }

        return null;
    }

    public static function toArray($value): ?array
    {
        if ($value === null) {
            return null;
        }

        return (array) $value;
    }
}
