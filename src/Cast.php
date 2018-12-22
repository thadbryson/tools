<?php

declare(strict_types = 1);

namespace Tool\Support;

use Illuminate\Support\Arr;
use InvalidArgumentException;
use function filter_var;
use function settype;
use const FILTER_VALIDATE_BOOLEAN;
use const FILTER_VALIDATE_FLOAT;
use const FILTER_VALIDATE_INT;

/**
 * Cast Class
 */
class Cast
{
    protected static function prepareValues(array $casts, array $values): array
    {
        // Get DOT values array so we can search it easier.
        $values = Arr::dot($values);

        // Get any casts that aren't in $values.
        $notIn = array_diff($casts, $values);

        if (count($notIn) > 0) {
            throw new InvalidArgumentException('Casts not found in values: ' . implode(', ', $notIn));
        }

        return $values;
    }

    public static function all(array $casts, array $values): array
    {
        $values = static::prepareValues($casts, $values);

        foreach ($casts as $dot => $type) {

            // Set new casted value.
            $values[$dot] = static::cast($type, $values[$dot]);
        }

        // Now return "undotted" array.
        $return = [];

        foreach ($values as $dot => $value) {
            Arr::get($return, $dot, $value);
        }

        return $return;
    }

    /**
     * @param string $type
     * @param        $value
     *
     * @return bool|int|float|string|Clock
     * @throws InvalidArgumentException
     */
    public static function cast(string $type, $value)
    {
        $format = null;

        if (strpos($type, ':') !== false) {
            [$type, $format] = explode(':', $type);

            $format = $format ?? null;
        }

        settype($value, $type);

        switch ($type) {

            case 'bool':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);

            case 'int':
                return filter_var($value, FILTER_VALIDATE_INT);

            case 'float':
                return filter_var($value, FILTER_VALIDATE_FLOAT);

            case 'string':
                return Str::make($value);

            case 'datetime':
                return Clock::createFromFormat($format ?? 'Y-m-d H:i:s', (string) $value);

            case 'collection':
                return Collection::make($value);

            default:
                throw new InvalidArgumentException('Unknown cast type: ' . $type);
        }
    }
}
