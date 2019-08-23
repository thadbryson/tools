<?php

declare(strict_types = 1);

namespace Tool\Validation\Helper;

use Assert\Assertion as BaseAssertion;
use Illuminate\Support\Arr;
use Tool\Cast;
use function file_exists;
use function get_class;
use function implode;
use function in_array;
use function is_dir;
use function is_file;
use function is_float;
use function is_object;
use function is_string;
use function is_subclass_of;
use function strtolower;

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
     * @param string $message      = null
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
     * @param string|null          $propertyPath = null
     *
     * @return bool
     */
    public static function notInArray($value, array $choices, $message = null, $propertyPath = null): bool
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
     * Determines that the named method is defined in the provided object.
     *
     * @param mixed                $method
     * @param mixed                $object
     * @param string|callable|null $message      = null
     * @param string|null          $propertyPath = null
     *
     * @return bool
     */
    public static function methodExists($method, $object, $message = null, $propertyPath = null): bool
    {
        if (is_object($object)) {
            return parent::methodExists($method, $object, $message, $propertyPath);
        }

        // Has to be a class string.
        static::classExists($object, $message, $propertyPath);

        // is_callable() handles magic __call() methods.
        if (is_callable([$object, $method]) === false) {

            $message = \sprintf(
                static::generateMessage($message ?: 'Expected method "%s()" does not exist on object %s.'),
                $method,
                static::typeString($object)
            );

            throw static::createException($method, $message, static::INVALID_METHOD, $propertyPath,
                ['object' => static::typeString($object)]);
        }

        return true;
    }

    /**
     * Value is of type of class in array $type.
     *
     * @param array    $values
     * @param string[] $types
     * @param string   $message      = null
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
     * Is value a class name or object an subclass of $parentClass
     *
     * @param mixed  $classOrObject
     * @param string $parentClass
     * @param string $message      = null
     * @param string $propertyPath = null
     *
     * @return bool
     */
    public static function isSubclassOf($value, string $parentClass, string $message = null, string $propertyPath = null): bool
    {
        if (is_object($value)) {
            $value = get_class($value);
        }

        $message = \sprintf(
            static::generateMessage($message ?: '%s is not a subclass of %s'),
            static::typeString($value),
            static::typeString($parentClass)
        );

        if ($value !== $parentClass && is_subclass_of($value, $parentClass) === false) {

            throw static::createException($value, $message, 400, $propertyPath);
        }

        return true;
    }

    /**
     * Value is not in $array.
     *
     * @param mixed  $classOrObject
     * @param string $message      = null
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

    public static function truthy($value, string $message = null, string $propertyPath = null): bool
    {
        if (Cast::toBoolean($value) !== true) {
            throw static::createException($value, $message ?? '%s is not a truth-like value.', $propertyPath);
        }

        return true;
    }

    public static function falsey($value, string $message = null, string $propertyPath = null): bool
    {
        if (Cast::toBoolean($value) !== false) {
            throw static::createException($value, $message ?? '%s is not a false-like value.', $propertyPath);
        }

        return true;
    }

    /**
     * Is $value a valid filepath to an existing file or directory?
     *
     * @param             $filepath
     * @param string|null $message
     * @param string|null $propertyPath
     * @return bool
     */
    public static function filepath($filepath, string $message = null, string $propertyPath = null): bool
    {
        if (is_string($filepath) === false || file_exists($filepath) === false) {

            $message = \sprintf(
                static::generateMessage($message ?? 'Not an existing filepath: %s'),
                $filepath
            );

            throw static::createException($filepath, $message, $propertyPath);
        }

        return true;
    }

    /**
     * Is $value a valid filepath to an existing file or directory?
     *
     * @param        $filepath
     * @param string $extension
     * @param string $message      = null
     * @param string $propertyPath = null
     * @return bool
     */
    public static function fileExtension($filepath, string $extension, string $message = null, string $propertyPath = null): bool
    {
        $extension = ltrim($extension, '.');

        static::file($filepath);

        $message = sprintf('Filepath %s must have extension "%s".', $filepath, $extension);

        static::eq(pathinfo($filepath, PATHINFO_EXTENSION), $extension, $message, $propertyPath);

        return true;
    }

    /**
     * Is $filepath an actual, existing file?
     *
     * @param             $filepath
     * @param string|null $message
     * @param string|null $propertyPath
     * @return bool
     */
    public static function notFile($filepath, string $message = null, string $propertyPath = null): bool
    {
        if (is_string($filepath) === false || is_file($filepath)) {

            $message = \sprintf(
                static::generateMessage($message ?? 'Filepath %s cannot be a file.'),
                $filepath
            );

            throw static::createException($filepath, $message, $propertyPath);
        }

        return true;
    }

    /**
     * Is $filepath an actual, existing file?
     *
     * @param             $filepath
     * @param string|null $message
     * @param string|null $propertyPath
     * @return bool
     */
    public static function notDirectory($filepath, string $message = null, string $propertyPath = null): bool
    {
        if (is_string($filepath) === false || is_dir($filepath)) {

            $message = \sprintf(
                static::generateMessage($message ?? 'Filepath %s cannot be a directory.'),
                $filepath
            );

            throw static::createException($filepath, $message, $propertyPath);
        }

        return true;
    }

    public static function latitude($value, string $message = null, string $propertyPath = null): bool
    {
        if (is_float($value) && $value >= -90 && $value <= 90) {
            return true;
        }

        throw static::createException($value, $message ?? '%s is not a valid latitude.', $propertyPath);
    }

    public static function longitude($value, string $message = null, string $propertyPath = null): bool
    {
        if (is_float($value) && $value >= -180 && $value <= 180) {
            return true;
        }

        throw static::createException($value, $message ?? '%s is not a valid longitude.', $propertyPath);
    }

    /**
     * Helper method that handles building the assertion failure exceptions.
     * They are returned from this method so that the stack trace still shows
     * the assertions method.
     *
     * @param mixed           $value
     * @param string|callable $message
     * @param int             $code
     * @param string|null     $propertyPath
     * @param array           $constraints
     *
     * @return mixed
     */
    protected static function createException($value, $message, $code, $propertyPath = null, array $constraints = [])
    {
        return parent::createException($value, $message, 400, $propertyPath, $constraints);
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
}
