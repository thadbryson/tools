<?php

declare(strict_types = 1);

namespace Tool\Support\Validation;

use BadMethodCallException;
use Closure;
use Countable;
use Illuminate\Support\Arr;
use Webmozart\Assert\Assert as BaseAssert;
use function implode;
use function in_array;
use function is_object;
use function is_string;
use function method_exists;

/**
 * Wrap Webmozart Assert class  in this.
 * - Returns value passed in for testing if successful.
 *
 * @method static string string($value, $message = '')
 * @method static string stringNotEmpty($value, $message = '')
 * @method static int integer($value, $message = '')
 * @method static int|string integerish($value, $message = '')
 * @method static int float($value, $message = '')
 * @method static int|float|string numeric($value, $message = '')
 * @method static int|float natural($value, $message = '')
 * @method static bool boolean($value, $message = '')
 * @method static bool|int|float scalar($value, $message = '')
 * @method static object object($value, $message = '')
 * @method static resource resource($value, $type = null, $message = '')
 * @method static callable isCallable($value, $message = '')
 * @method static array isArray($value, $message = '')
 * @method static iterable isTraversable($value, $message = '')
 * @method static array|\ArrayAccess isArrayAccessible($value, $message = '')
 * @method static Countable|array isCountable($value, $message = '')
 * @method static iterable isIterable($value, $message = '')
 * @method static object isInstanceOf($value, $class, $message = '')
 * @method static object notInstanceOf($value, $class, $message = '')
 * @method static object isInstanceOfAny($value, array $classes, $message = '')
 * @method static mixed isEmpty($value, $message = '')
 * @method static mixed notEmpty($value, $message = '')
 * @method static null null($value, $message = '')
 * @method static mixed notNull($value, $message = '')
 * @method static true true($value, $message = '')
 * @method static false false($value, $message = '')
 * @method static int|float|string eq($value, $value2, $message = '')
 * @method static int|float|string notEq($value, $value2, $message = '')
 * @method static mixed same($value, $value2, $message = '')
 * @method static mixed notSame($value, $value2, $message = '')
 * @method static int|float|string greaterThan($value, $limit, $message = '')
 * @method static int|float|string greaterThanEq($value, $limit, $message = '')
 * @method static int|float|string lessThan($value, $limit, $message = '')
 * @method static int|float|string lessThanEq($value, $limit, $message = '')
 * @method static int|float|string range($value, $min, $max, $message = '')
 * @method static mixed oneOf($value, array $values, $message = '')
 * @method static string contains($value, $subString, $message = '')
 * @method static string notContains($value, $subString, $message = '')
 * @method static string notWhitespaceOnly($value, $message = '')
 * @method static string startsWith($value, $prefix, $message = '')
 * @method static string startsWithLetter($value, $message = '')
 * @method static string endsWith($value, $suffix, $message = '')
 * @method static string regex($value, $pattern, $message = '')
 * @method static string alpha($value, $message = '')
 * @method static string digits($value, $message = '')
 * @method static string alnum($value, $message = '')
 * @method static string lower($value, $message = '')
 * @method static string upper($value, $message = '')
 * @method static string length($value, $length, $message = '')
 * @method static string minLength($value, $min, $message = '')
 * @method static string maxLength($value, $max, $message = '')
 * @method static string lengthBetween($value, $min, $max, $message = '')
 * @method static string fileExists($value, $message = '')
 * @method static string file($value, $message = '')
 * @method static string directory($value, $message = '')
 * @method static string readable($value, $message = '')
 * @method static string writable($value, $message = '')
 * @method static string classExists($value, $message = '')
 * @method static string subclassOf($value, $class, $message = '')
 * @method static string implementsInterface($value, $interface, $message = '')
 * @method static string propertyExists($classOrObject, $property, $message = '')
 * @method static string propertyNotExists($classOrObject, $property, $message = '')
 * @method static string methodExists($classOrObject, $method, $message = '')
 * @method static string methodNotExists($classOrObject, $method, $message = '')
 * @method static array keyExists($array, $key, $message = '')
 * @method static array keyNotExists($array, $key, $message = '')
 * @method static array count($array, $number, $message = '')
 * @method static array minCount($array, $min, $message = '')
 * @method static array maxCount($array, $max, $message = '')
 * @method static int|float|string countBetween($array, $min, $max, $message = '')
 * @method static string uuid($value, $message = '')
 * @method static Closure throws(Closure $expression, $class = 'Exception', $message = '')
 * @method static null|string nullOrString($value, $message = '')
 * @method static null|string nullOrStringNotEmpty($value, $message = '')
 * @method static null|int nullOrInteger($value, $message = '')
 * @method static null|int|string nullOrIntegerish($value, $message = '')
 * @method static null|float nullOrFloat($value, $message = '')
 * @method static null|string|int|float nullOrNumeric($value, $message = '')
 * @method static null|bool nullOrBoolean($value, $message = '')
 * @method static null|bool|int|float nullOrScalar($value, $message = '')
 * @method static null|object nullOrObject($value, $message = '')
 * @method static null|object nullOrResource($value, $type = null, $message = '')
 * @method static null|object nullOrIsCallable($value, $message = '')
 * @method static null|object nullOrIsArray($value, $message = '')
 * @method static null|object nullOrIsTraversable($value, $message = '')
 * @method static null|object nullOrIsArrayAccessible($value, $message = '')
 * @method static null|object nullOrIsCountable($value, $message = '')
 * @method static null|object nullOrIsInstanceOf($value, $class, $message = '')
 * @method static null|object nullOrNotInstanceOf($value, $class, $message = '')
 * @method static null|object nullOrIsInstanceOfAny($value, $classes, $message = '')
 * @method static null|mixed nullOrIsEmpty($value, $message = '')
 * @method static null|mixed nullOrNotEmpty($value, $message = '')
 * @method static null|true nullOrTrue($value, $message = '')
 * @method static null|false nullOrFalse($value, $message = '')
 * @method static null|mixed nullOrEq($value, $value2, $message = '')
 * @method static null|mixed nullOrNotEq($value, $value2, $message = '')
 * @method static null|mixed nullOrSame($value, $value2, $message = '')
 * @method static null|int nullOrNotSame($value, $value2, $message = '')
 * @method static null|int nullOrGreaterThan($value, $value2, $message = '')
 * @method static null|int nullOrGreaterThanEq($value, $value2, $message = '')
 * @method static null|int nullOrLessThan($value, $value2, $message = '')
 * @method static null|int nullOrLessThanEq($value, $value2, $message = '')
 * @method static null|int nullOrRange($value, $min, $max, $message = '')
 * @method static null|mixed nullOrOneOf($value, $values, $message = '')
 * @method static null|string nullOrContains($value, $subString, $message = '')
 * @method static null|string nullOrNotContains($value, $subString, $message = '')
 * @method static null|string nullOrNotWhitespaceOnly($value, $message = '')
 * @method static null|string nullOrStartsWith($value, $prefix, $message = '')
 * @method static null|string nullOrStartsWithLetter($value, $message = '')
 * @method static null|string nullOrEndsWith($value, $suffix, $message = '')
 * @method static null|string nullOrRegex($value, $pattern, $message = '')
 * @method static null|string nullOrAlpha($value, $message = '')
 * @method static null|string nullOrDigits($value, $message = '')
 * @method static null|string nullOrAlnum($value, $message = '')
 * @method static null|int nullOrLower($value, $message = '')
 * @method static null|int nullOrUpper($value, $message = '')
 * @method static null|int nullOrLength($value, $length, $message = '')
 * @method static null|int nullOrMinLength($value, $min, $message = '')
 * @method static null|int nullOrMaxLength($value, $max, $message = '')
 * @method static null|int nullOrLengthBetween($value, $min, $max, $message = '')
 * @method static null|string nullOrFileExists($value, $message = '')
 * @method static null|string nullOrFile($value, $message = '')
 * @method static null|string nullOrDirectory($value, $message = '')
 * @method static null|string nullOrReadable($value, $message = '')
 * @method static null|string nullOrWritable($value, $message = '')
 * @method static null|string nullOrClassExists($value, $message = '')
 * @method static null|string nullOrSubclassOf($value, $class, $message = '')
 * @method static null|string nullOrImplementsInterface($value, $interface, $message = '')
 * @method static null|string nullOrPropertyExists($value, $property, $message = '')
 * @method static null|string nullOrPropertyNotExists($value, $property, $message = '')
 * @method static null|string nullOrMethodExists($value, $method, $message = '')
 * @method static string|null nullOrMethodNotExists($value, $method, $message = '')
 * @method static int|null|string nullOrKeyExists($value, $key, $message = '')
 * @method static int|null|string nullOrKeyNotExists($value, $key, $message = '')
 * @method static int|null nullOrCount($value, $key, $message = '')
 * @method static int|null nullOrMinCount($value, $min, $message = '')
 * @method static int|null nullOrMaxCount($value, $max, $message = '')
 * @method static int|null nullCountBetween($value, $min, $max, $message = '')
 * @method static array nullOrUuid($values, $message = '')
 * @method static array allString($values, $message = '')
 * @method static array allStringNotEmpty($values, $message = '')
 * @method static array allInteger($values, $message = '')
 * @method static array allIntegerish($values, $message = '')
 * @method static array allFloat($values, $message = '')
 * @method static array allNumeric($values, $message = '')
 * @method static array allBoolean($values, $message = '')
 * @method static array allScalar($values, $message = '')
 * @method static array allObject($values, $message = '')
 * @method static array allResource($values, $type = null, $message = '')
 * @method static array allIsCallable($values, $message = '')
 * @method static array allIsArray($values, $message = '')
 * @method static array allIsTraversable($values, $message = '')
 * @method static array allIsArrayAccessible($values, $message = '')
 * @method static array allIsCountable($values, $message = '')
 * @method static array allIsInstanceOf($values, $class, $message = '')
 * @method static array allNotInstanceOf($values, $class, $message = '')
 * @method static array allIsInstanceOfAny($values, $classes, $message = '')
 * @method static array allNull($values, $message = '')
 * @method static array allNotNull($values, $message = '')
 * @method static array allIsEmpty($values, $message = '')
 * @method static array allNotEmpty($values, $message = '')
 * @method static array allTrue($values, $message = '')
 * @method static array allFalse($values, $message = '')
 * @method static array allEq($values, $value2, $message = '')
 * @method static array allNotEq($values, $value2, $message = '')
 * @method static array allSame($values, $value2, $message = '')
 * @method static array allNotSame($values, $value2, $message = '')
 * @method static array allGreaterThan($values, $value2, $message = '')
 * @method static array allGreaterThanEq($values, $value2, $message = '')
 * @method static array allLessThan($values, $value2, $message = '')
 * @method static array allLessThanEq($values, $value2, $message = '')
 * @method static array allRange($values, $min, $max, $message = '')
 * @method static array allOneOf($values, $values, $message = '')
 * @method static array allContains($values, $subString, $message = '')
 * @method static array allNotContains($values, $subString, $message = '')
 * @method static array allNotWhitespaceOnly($values, $message = '')
 * @method static array allStartsWith($values, $prefix, $message = '')
 * @method static array allStartsWithLetter($values, $message = '')
 * @method static array allEndsWith($values, $suffix, $message = '')
 * @method static array allRegex($values, $pattern, $message = '')
 * @method static array allAlpha($values, $message = '')
 * @method static array allDigits($values, $message = '')
 * @method static array allAlnum($values, $message = '')
 * @method static array allLower($values, $message = '')
 * @method static array allUpper($values, $message = '')
 * @method static array allLength($values, $length, $message = '')
 * @method static array allMinLength($values, $min, $message = '')
 * @method static array allMaxLength($values, $max, $message = '')
 * @method static array allLengthBetween($values, $min, $max, $message = '')
 * @method static array allFileExists($values, $message = '')
 * @method static array allFile($values, $message = '')
 * @method static array allDirectory($values, $message = '')
 * @method static array allReadable($values, $message = '')
 * @method static array allWritable($values, $message = '')
 * @method static array allClassExists($values, $message = '')
 * @method static array allSubclassOf($values, $class, $message = '')
 * @method static array allImplementsInterface($values, $interface, $message = '')
 * @method static array allPropertyExists($values, $property, $message = '')
 * @method static array allPropertyNotExists($values, $property, $message = '')
 * @method static array allMethodExists($values, $method, $message = '')
 * @method static array allMethodNotExists($values, $method, $message = '')
 * @method static array allKeyExists($values, $key, $message = '')
 * @method static array allKeyNotExists($values, $key, $message = '')
 * @method static array allCount($values, $key, $message = '')
 * @method static array allMinCount($values, $min, $message = '')
 * @method static array allMaxCount($values, $max, $message = '')
 * @method static array allCountBetween($values, $min, $max, $message = '')
 * @method static array allUuid($values, $message = '')
 * @method static mixed equals($value, $equals, string $message = '')
 * @method static mixed oneOfAType($value, array $types, string $message = '')
 * @method static array allOfAnyType(array $values, array $types, string $message = ''): array
 * @method static mixed inArray($value, array $array, string $message = '')
 * @method static mixed notInArray($value, array $array, string $message = '')
 * @method static string dotKeyExists($key, array $array, string $message = ''): array
 * @method static array notDotKeyExists($keys, array $array, string $message = ''): array
 * @method static string|object classOrObject($value, string $message = '')
 */
class Assert
{
    /**
     * Call Webmozart Assert::* test method. Returns value to test
     * if it is valid.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     * @throws \InvalidArgumentException
     * @throws BadMethodCallException
     */
    public static function __callStatic(string $method, array $arguments)
    {
        $internalMethod = '_' . $method;

        // An internal method?
        if (method_exists(static::class, $internalMethod) === true) {
            static::{$internalMethod}(...$arguments);
        }
        // One of the base methods?
        else {
            BaseAssert::{$method}(...$arguments);
        }

        return $arguments[0];
    }

    /**
     * Value equals (strict) second value given.
     */
    protected static function _equals($value, $equals, string $message = '')
    {
        BaseAssert::true($value === $equals, $message ?: sprintf('%s does not equal %s',
            static::valueToString($value), static::valueToString($equals)));

        return $value;
    }

    /**
     * Value is of type of class in array $type.
     *
     * @param array    $values
     * @param string[] $types
     * @param string   $message
     *
     * @return mixed
     */
    protected static function _oneOfAType($value, array $types, string $message = '')
    {
        $valueType = static::typeToString($value);

        if (in_array($valueType, $types, false) === false) {

            static::reportInvalidArgument(sprintf(
                $message ?: 'Expected one of any type "%s", got: %s',
                implode(', ', $types),
                static::typeToString($value)
            ));
        }

        return $value;
    }

    /**
     * Value is of type of class in array $type.
     *
     * @param array    $values
     * @param string[] $types
     * @param string   $message
     *
     * @return array
     */
    protected static function _allOfAnyType(array $values, array $types, string $message = ''): array
    {
        foreach ($values as $value) {
            static::_oneOfAType($value, $types, $message);
        }

        return $values;
    }

    /**
     * Value is in $array.
     *
     * @param mixed  $value
     * @param array  $array
     * @param string $message = ''
     *
     * @return mixed
     */
    protected static function _inArray($value, array $array, string $message = '')
    {
        if (in_array($value, $array, true) === false) {

            static::reportInvalidArgument(
                sprintf($message ?: 'Value %s was not found.', static::typeToString($value))
            );
        }

        return $value;
    }

    /**
     * Value is not in $array.
     *
     * @param mixed  $value
     * @param array  $array
     * @param string $message = ''
     *
     * @return mixed
     */
    protected static function _notInArray($value, array $array, string $message = '')
    {
        if (in_array($value, $array, true) === true) {

            static::reportInvalidArgument(
                sprintf($message ?: 'Value %s is not allowed.', static::typeToString($value))
            );
        }

        return $value;
    }

    /**
     * Value is in $array.
     *
     * @param string|string[] $keys
     * @param array           $array
     * @param string          $message = ''
     *
     * @return array
     */
    protected static function _dotKeyExists($keys, array $array, string $message = ''): array
    {
        if (Arr::has($array, $keys) === false) {

            static::reportInvalidArgument(
                sprintf($message ?: 'Keys %s are not allowed.', static::valueToString($keys))
            );
        }

        return $array;
    }

    /**
     * Value is not in $array.
     *
     * @param string|string[] $keys
     * @param array           $array
     * @param string          $message = ''
     *
     * @return array
     */
    protected static function _notDotKeyExists($keys, array $array, string $message = ''): array
    {
        if (Arr::has($array, $keys)) {

            static::reportInvalidArgument(
                sprintf($message ?: 'Keys %s are not allowed.', static::valueToString($keys))
            );
        }

        return $array;
    }

    /**
     * Value is not in $array.
     *
     * @param mixed  $classOrObject
     * @param string $message = ''
     *
     * @return object|string
     */
    protected static function _classOrObject($value, string $message = '')
    {
        if (is_object($value)) {
            return $value;
        }

        Assert::string($value, sprintf('Expected an object or class string. It is not a string, given %s',
            static::valueToString($value)));

        Assert::classExists($value, sprintf('%s is not an existing class.', $value));

        return $value;
    }

    protected static function valueToString($value): string
    {
        if (null === $value) {
            return 'null';
        }

        if (true === $value) {
            return 'true';
        }

        if (false === $value) {
            return 'false';
        }

        if (is_array($value)) {
            return 'array';
        }

        if (is_object($value)) {
            return get_class($value);
        }

        if (is_string($value)) {
            return '"' . $value . '"';
        }

        return (string) $value;
    }

    protected static function typeToString($value): string
    {
        return is_object($value) ? get_class($value) : gettype($value);
    }

    protected static function reportInvalidArgument(string $message): void
    {
        throw new \InvalidArgumentException($message);
    }
}
