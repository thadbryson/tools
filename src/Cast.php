<?php

declare(strict_types = 1);

namespace Tool;

use InvalidArgumentException;
use function array_replace_recursive;
use function is_array;
use function is_float;
use function is_int;
use function is_object;
use function is_string;
use function json_decode;
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
            case 'boolean':
                return static::toBoolean($value);

            case 'int':
            case 'integer':
                return static::toInteger($value);

            case 'float':
                return static::toFloat($value);

            case 'string':
                return static::toString($value);

            case 'array':
                return static::toArray($value);

            case 'datetime':
                return static::toDateTime($value);

            case 'collection':
                return static::toCollection($value);

            default:
                throw new InvalidArgumentException('Invalid cast type given: ' . $type);
        }
    }

    public static function toBoolean($value): ?bool
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            $value = strtolower($value);
        }

        if (in_array($value, [true, 'true', 1, '1', 'on', 'yes', 'y', 't'], true)) {
            return true;
        }

        if (in_array($value, [false, 'false', 0, '0', 'off', 'no', 'n', 'f'], true)) {
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
        if ($value === null || is_array($value)) {
            return $value;
        }

        // Collection object?
        if (is_object($value)) {
            return Collection::make($value)->all();
        }

        // JSON
        if (is_string($value)) {
            return (array) json_decode($value, true);
        }

        return (array) $value;
    }

    public static function toCollection($value): ?Collection
    {
        $value = static::toArray($value);

        if ($value === null) {
            return null;
        }

        return new Collection($value);
    }

    public static function toDateTime($value): ?Clock
    {
        return Clock::makeOrNull($value);
    }
}
