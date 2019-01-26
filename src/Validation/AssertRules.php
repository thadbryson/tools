<?php

declare(strict_types = 1);

namespace Tool\Validation;

use Assert\Assertion as BaseAssertion;
use Illuminate\Support\Arr;
use function implode;
use function in_array;
use function is_object;

/**
 * Wrap Webmozart Assert class  in this.
 * - Returns value passed in for testing if successful.
 */
class AssertRules extends BaseAssertion
{
    /**
     * @alias same()
     */
    public static function equals($values, $value2, string $message = null, string $propertyPath = null): bool
    {
        return parent::same($values, $value2, $message, $propertyPath);
    }

    /**
     * Assert that $value is a non-empty string.
     *
     * @param mixed  $value
     * @param string $message = null
     * @param string $propertyPath = null
     * @return bool
     */
    public static function stringNotEmpty($value, string $message = null, string $propertyPath = null): bool
    {
        static::string($value, null, $propertyPath);

        return parent::notEmpty($value, $message, $propertyPath);
    }

    /**
     * Assert that value is not in array of choices.
     *
     * @param mixed                $value
     * @param array                $choices
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     */
    public static function notInArray($value, array $choices, $message = null, $propertyPath = null)
    {
        if (true === \in_array($value, $choices, true)) {
            $message = \sprintf(
                static::generateMessage($message ?: 'Value "%s" was not expected to be an element of the values: %s'),
                static::stringify($value),
                static::stringify($choices)
            );

            throw static::createException($value, $message, static::INVALID_VALUE_IN_ARRAY, $propertyPath,
                ['choices' => $choices]);
        }

        return true;
    }

    /**
     * Value is of type of class in array $type.
     *
     * @param array    $values
     * @param string[] $types
     * @param string   $message = null
     * @param string   $propertyPath = null
     *
     * @return bool
     */
    public static function allOfAnyType(array $values, array $types, string $message = null,
        string $propertyPath = null): bool
    {
        foreach ($values as $value) {
            static::oneOfAType($value, $types, $message, $propertyPath);
        }

        return true;
    }

    /**
     * Value is of type of class in array $type.
     */
    public static function oneOfAType($value, array $types, string $message = null, string $propertyPath = null): bool
    {
        $valueType = static::typeString($value);

        if (in_array($valueType, $types, false) === true) {
            return true;
        }

        throw static::createException($valueType, sprintf(
            $message ?: 'Expected one of any type "%s", got: %s',
            implode(', ', $types),
            $valueType
        ), 400, $propertyPath);
    }

    protected static function typeString($value): string
    {
        if (is_object($value)) {
            return '\\' . get_class($value);
        }
        elseif (is_string($value) && class_exists($value)) {
            return '\\' . trim($value, '\\');
        }

        $type = strtolower(gettype($value));

        switch ($type) {
            case 'bool':
            case 'boolean':
                return 'bool';

            case 'integer':
            case 'int':
                return 'int';

            case 'float':
            case 'double':
                return 'float';

            default:
                return $type;
        }
    }

    /**
     * Value is in $array.
     *
     * @param string|string[] $keys
     * @param array           $array
     * @param string          $message = ''
     *
     * @return bool
     */
    public static function dotKeyExists($keys, array $array, string $message = null, string $propertyPath = null): bool
    {
        if (Arr::has($array, $keys) === false) {

            throw static::createException($keys, sprintf(
                $message ?: 'Keys %s are not allowed.', static::stringify($keys)
            ), 400, $propertyPath);
        }

        return true;
    }

    /**
     * Value is not in $array.
     *
     * @param string|string[] $keys
     * @param array           $array
     * @param string          $message = ''
     *
     * @return bool
     */
    public static function notDotKeyExists($keys, array $array, string $message = null,
        string $propertyPath = null): bool
    {
        if (Arr::has($array, $keys)) {

            throw static::createException($keys, sprintf(
                $message ?: 'Keys %s are not allowed.', static::stringify($keys)
            ), 400, $propertyPath);
        }

        return true;
    }

    /**
     * Value is not in $array.
     *
     * @param mixed  $classOrObject
     * @param string $message = null
     * @param string $propertyPath = null
     *
     * @return bool
     */
    public static function classOrObject($value, string $message = null, string $propertyPath = null): bool
    {
        if (is_object($value)) {
            return true;
        }

        static::string($value, sprintf($message ?: 'Expected an object or class string. It is not a string, given %s',
            static::stringify($value)), $propertyPath);

        static::classExists($value, sprintf($message ?: '%s is not an existing class.', $value));

        return true;
    }

    /**
     * Determines that the named method is defined in the provided object.
     *
     * @param string               $value
     * @param mixed                $object
     * @param string|callable|null $message
     * @param string|null          $propertyPath
     *
     * @return bool
     */
    public static function methodExists($value, $object, $message = null, $propertyPath = null)
    {
        if (is_object($object) || is_string($object) && method_exists($object, $value)) {
            return true;
        }

        $message = \sprintf(
            static::generateMessage($message ?: 'Expected "%s" does not exist in provided object.'),
            static::stringify($value)
        );

        throw static::createException($value, $message, static::INVALID_METHOD, $propertyPath,
            ['object' => \get_class($object)]);
    }
}