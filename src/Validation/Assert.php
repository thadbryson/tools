<?php

declare(strict_types = 1);

namespace Tool\Validation;

/**
 * Class Assert
 *
 * Call assertion rule, return value if the value is valid.
 * If not valid - throw \InvalidArgumentException
 *
 * @method static mixed equals($value, $value2, $message = null, $propertyPath = null)
 * @method static mixed eq($value, $value2, string $message = null, string $propertyPath = null)
 * @method static mixed same($value, $value2, string $message = null, string $propertyPath = null)
 * @method static mixed notEq($value1, $value2, string $message = null, string $propertyPath = null)
 * @method static mixed notSame($value1, $value2, string $message = null, string $propertyPath = null)
 * @method static mixed notInArray($value, array $choices, string $message = null, string $propertyPath = null)
 * @method static int integer($value, string $message = null, string $propertyPath = null)
 * @method static float float($value, string $message = null, string $propertyPath = null)
 * @method static int digit($value, string $message = null, string $propertyPath = null)
 * @method static int|string integerish($value, string $message = null, string $propertyPath = null)
 * @method static bool boolean($value, string $message = null, string $propertyPath = null)
 * @method static mixed scalar($value, string $message = null, string $propertyPath = null)
 * @method static mixed notEmpty($value, string $message = null, string $propertyPath = null)
 * @method static string noContent($value, string $message = null, string $propertyPath = null)
 * @method static null null($value, string $message = null, string $propertyPath = null)
 * @method static mixed notNull($value, string $message = null, string $propertyPath = null)
 * @method static string string($value, string $message = null, string $propertyPath = null)
 * @method static string regex($value, $pattern, string $message = null, string $propertyPath = null)
 * @method static mixed notRegex($value, $pattern, string $message = null, string $propertyPath = null)
 * @method static string length($value, $length, string $message = null, string $propertyPath = null, string $encoding
 *     = 'utf8')
 * @method static string minLength($value, $minLength, string $message = null, string $propertyPath = null, string
 *     $encoding = 'utf8')
 * @method static string maxLength($value, $maxLength, string $message = null, string $propertyPath = null, string
 *     $encoding = 'utf8')
 * @method static string betweenLength($value, $minLength, $maxLength, string $message = null, string $propertyPath =
 *     null, string $encoding = 'utf8')
 * @method static string startsWith($string, $needle, string $message = null, string $propertyPath = null, string
 *     $encoding = 'utf8')
 * @method static string endsWith($string, $needle, string $message = null, string $propertyPath = null, string
 *     $encoding = 'utf8')
 * @method static string contains($string, $needle, string $message = null, string $propertyPath = null, string
 *     $encoding = 'utf8')
 * @method static string notContains($string, $needle, string $message = null, string $propertyPath = null, string
 *     $encoding = 'utf8')
 * @method static mixed choice($value, array $choices, string $message = null, string $propertyPath = null)
 * @method static mixed inArray($value, array $choices, string $message = null, string $propertyPath = null)
 * @method static string|int numeric($value, string $message = null, string $propertyPath = null)
 * @method static resource isResource($value, string $message = null, string $propertyPath = null)
 * @method static array isArray($value, string $message = null, string $propertyPath = null)
 * @method static iterable isTraversable($value, string $message = null, string $propertyPath = null)
 * @method static array|\ArrayAccess isArrayAccessible($value, string $message = null, string $propertyPath = null)
 * @method static array isCountable($value, string $message = null, string $propertyPath = null)
 * @method static string|int keyExists($value, $key, string $message = null, string $propertyPath = null)
 * @method static string|int keyNotExists($value, $key, string $message = null, string $propertyPath = null)
 * @method static string|int keyIsset($value, $key, string $message = null, string $propertyPath = null)
 * @method static mixed notEmptyKey($value, $key, string $message = null, string $propertyPath = null)
 * @method static string notBlank($value, string $message = null, string $propertyPath = null)
 * @method static object isInstanceOf($value, $className, string $message = null, string $propertyPath = null)
 * @method static object notIsInstanceOf($value, $className, string $message = null, string $propertyPath = null)
 * @method static object subclassOf($value, $className, string $message = null, string $propertyPath = null)
 * @method static int|float range($value, $minValue, $maxValue, string $message = null, string $propertyPath = null)
 * @method static int|float min($value, $minValue, string $message = null, string $propertyPath = null)
 * @method static int|float max($value, $maxValue, string $message = null, string $propertyPath = null)
 * @method static string file($value, string $message = null, string $propertyPath = null)
 * @method static string directory($value, string $message = null, string $propertyPath = null)
 * @method static string readable($value, string $message = null, string $propertyPath = null)
 * @method static string writeable($value, string $message = null, string $propertyPath = null)
 * @method static string email($value, string $message = null, string $propertyPath = null)
 * @method static string url($value, string $message = null, string $propertyPath = null)
 * @method static string alnum($value, string $message = null, string $propertyPath = null)
 * @method static true true($value, string $message = null, string $propertyPath = null)
 * @method static false false($value, string $message = null, string $propertyPath = null)
 * @method static string classExists($value, string $message = null, string $propertyPath = null)
 * @method static string interfaceExists($value, string $message = null, string $propertyPath = null)
 * @method static string implementsInterface($class, $interfaceName, string $message = null, string $propertyPath =
 *     null)
 * @method static string isJsonString($value, string $message = null, string $propertyPath = null)
 * @method static string uuid($value, string $message = null, string $propertyPath = null)
 * @method static string e164($value, string $message = null, string $propertyPath = null)
 * @method static \Countable|array count($countable, $count, string $message = null, string $propertyPath = null)
 * @method static \Countable|array minCount($countable, $count, string $message = null, string $propertyPath = null)
 * @method static \Countable|array maxCount($countable, $count, string $message = null, string $propertyPath = null)
 * @method static array choicesNotEmpty(array $values, array $choices, string $message = null, string $propertyPath =
 *     null)
 * @method static string methodExists($value, $object, string $message = null, string $propertyPath = null)
 * @method static object isObject($value, string $message = null, string $propertyPath = null)
 * @method static int|float lessThan($value, $limit, string $message = null, string $propertyPath = null)
 * @method static int|float lessOrEqualThan($value, $limit, string $message = null, string $propertyPath = null)
 * @method static int|float greaterThan($value, $limit, string $message = null, string $propertyPath = null)
 * @method static int|float greaterOrEqualThan($value, $limit, string $message = null, string $propertyPath = null)
 * @method static int|float between($value, $lowerLimit, $upperLimit, string $message = null, string $propertyPath =
 *     null)
 * @method static int|float betweenExclusive($value, $lowerLimit, $upperLimit, string $message = null, string
 *     $propertyPath = null)
 * @method static string extensionLoaded($value, string $message = null, string $propertyPath = null)
 * @method static string|\DateTime date($value, $format, string $message = null, string $propertyPath = null)
 * @method static object|class objectOrClass($value, string $message = null, string $propertyPath = null)
 * @method static string propertyExists($value, $property, string $message = null, string $propertyPath = null)
 * @method static string[] propertiesExist($value, array $properties, string $message = null, string $propertyPath =
 *     null)
 * @method static string version($version1, $operator, $version2, string $message = null, string $propertyPath = null)
 * @method static string phpVersion($operator, $version, string $message = null, string $propertyPath = null)
 * @method static string extensionVersion($extension, $operator, $version, string $message = null, string $propertyPath
 *     = null)
 * @method static callable isCallable($value, string $message = null, string $propertyPath = null)
 * @method static callable satisfy($value, $callback, string $message = null, string $propertyPath = null)
 * @method static string ip($value, $flag = null, string $message = null, string $propertyPath = null)
 * @method static string ipv4($value, $flag = null, string $message = null, string $propertyPath = null)
 * @method static string ipv6($value, $flag = null, string $message = null, string $propertyPath = null)
 * @method static string defined($constant, string $message = null, string $propertyPath = null)
 * @method static string base64($value, string $message = null, string $propertyPath = null)
 * @method static string[] allAlnum(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static string[] allBase64(string $value, string|callable $message = null, string $propertyPath = null)
 * @method static int[]|float[] allBetween(mixed $value, mixed $lowerLimit, mixed $upperLimit, string $message = null,
 *     string $propertyPath = null)
 * @method static int[]|float[] allBetweenExclusive(mixed $value, mixed $lowerLimit, mixed $upperLimit, string $message
 *     = null, string $propertyPath = null)
 * @method static string[] allBetweenLength(mixed $value, int $minLength, int $maxLength, string|callable $message =
 *     null, string $propertyPath = null, string $encoding = 'utf8')
 * @method static bool[] allBoolean(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static mixed[] allChoice(mixed $value, array $choices, string|callable $message = null, string $propertyPath
 *     = null)
 * @method static array[] allChoicesNotEmpty(array $values, array $choices, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static string[] allClassExists(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static string[] allContains(mixed $string, string $needle, string|callable $message = null, string
 *     $propertyPath = null, string $encoding = 'utf8')
 * @method static array|\Countable[] allCount(array|\Countable|\ResourceBundle|\SimpleXMLElement $countable, int
 *     $count, string $message = null, string $propertyPath = null)
 * @method static string[]|\DateTime[] allDate(string $value, string $format, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static string[] allDefined(mixed $constant, string|callable $message = null, string $propertyPath = null)
 * @method static string[] allDigit(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static string[] allDirectory(string $value, string|callable $message = null, string $propertyPath = null)
 * @method static string[] allE164(string $value, string|callable $message = null, string $propertyPath = null)
 * @method static string[] allEmail(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static string[] allEndsWith(mixed $string, string $needle, string|callable $message = null, string
 *     $propertyPath = null, string $encoding = 'utf8')
 * @method static array allEq(mixed $value, mixed $value2, string|callable $message = null, string $propertyPath =
 *     null)
 * @method static string[] allExtensionLoaded(mixed $value, string|callable $message = null, string $propertyPath =
 *     null)
 * @method static string[] allExtensionVersion(string $extension, string $operator, mixed $version, string|callable
 *     $message = null, string $propertyPath = null)
 * @method static false[] allFalse(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static string[] allFile(string $value, string|callable $message = null, string $propertyPath = null)
 * @method static float[] allFloat(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static int[]|float[] allGreaterOrEqualThan(mixed $value, mixed $limit, string|callable $message = null,
 *     string $propertyPath = null)
 * @method static int[]|float[] allGreaterThan(mixed $value, mixed $limit, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static object[] allImplementsInterface(mixed $class, string $interfaceName, string|callable $message = null,
 *     string $propertyPath = null)
 * @method static array allInArray(mixed $value, array $choices, string|callable $message = null, string $propertyPath
 *     = null)
 * @method static int[] allInteger(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static int[]|string[] allIntegerish(mixed $value, string|callable $message = null, string $propertyPath =
 *     null)
 * @method static string[] allInterfaceExists(mixed $value, string|callable $message = null, string $propertyPath =
 *     null)
 * @method static string[] allIp(string $value, int $flag = null, string|callable $message = null, string $propertyPath
 *     = null)
 * @method static string[] allIpv4(string $value, int $flag = null, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static string[] allIpv6(string $value, int $flag = null, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static array[] allIsArray(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static array[]|\ArrayAccess[] allIsArrayAccessible(mixed $value, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static callable[] allIsCallable(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static array[]|\Countable[] allIsCountable(array|\Countable|\ResourceBundle|\SimpleXMLElement $value,
 *     string|callable $message = null, string $propertyPath = null)
 * @method static object[] allIsInstanceOf(mixed $value, string $className, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static string[] allIsJsonString(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static object[] allIsObject(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static resource[] allIsResource(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static array[] allIsTraversable(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static string[]|int[] allKeyExists(mixed $value, string|int $key, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static string[]|int[] allKeyIsset(mixed $value, string|int $key, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static string[]|int[] allKeyNotExists(mixed $value, string|int $key, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static string[] allLength(mixed $value, int $length, string|callable $message = null, string $propertyPath =
 *     null, string $encoding = 'utf8')
 * @method static int[]|float[] allLessOrEqualThan(mixed $value, mixed $limit, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static int[]|float[] allLessThan(mixed $value, mixed $limit, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static int[]|float[] allMax(mixed $value, mixed $maxValue, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static array[] allMaxCount(array|\Countable|\ResourceBundle|\SimpleXMLElement $countable, int $count, string
 *     $message = null, string $propertyPath = null)
 * @method static string[] allMaxLength(mixed $value, int $maxLength, string|callable $message = null, string
 *     $propertyPath = null, string $encoding = 'utf8')
 * @method static string[] allMethodExists(string $value, mixed $object, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static int[]|float[] allMin(mixed $value, mixed $minValue, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static int[]|float[] allMinCount(array|\Countable|\ResourceBundle|\SimpleXMLElement $countable, int $count,
 *     string $message = null, string $propertyPath = null)
 * @method static string[] allMinLength(mixed $value, int $minLength, string|callable $message = null, string
 *     $propertyPath = null, string $encoding = 'utf8')
 * @method static string[] allNoContent(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static string[] allNotBlank(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static string[] allNotContains(mixed $string, string $needle, string|callable $message = null, string
 *     $propertyPath = null, string $encoding = 'utf8')
 * @method static mixed allNotEmpty(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static array[] allNotEmptyKey(mixed $value, string|int $key, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static array allNotEq(mixed $value1, mixed $value2, string|callable $message = null, string $propertyPath =
 *     null)
 * @method static array allNotInArray(mixed $value, array $choices, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static object[] allNotIsInstanceOf(mixed $value, string $className, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static array allNotNull(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static string[] allNotRegex(mixed $value, string $pattern, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static array allNotSame(mixed $value1, mixed $value2, string|callable $message = null, string $propertyPath
 *     = null)
 * @method static array allNull(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static string[]|int[] allNumeric(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static object[]|string[] allObjectOrClass(mixed $value, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static string[] allPhpVersion(string $operator, mixed $version, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static string[] allPropertiesExist(mixed $value, array $properties, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static string[] allPropertyExists(mixed $value, string $property, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static int[]|float[] allRange(mixed $value, mixed $minValue, mixed $maxValue, string|callable $message =
 *     null, string $propertyPath = null)
 * @method static string[] allReadable(string $value, string|callable $message = null, string $propertyPath = null)
 * @method static string[] allRegex(mixed $value, string $pattern, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static array allSame(mixed $value, mixed $value2, string|callable $message = null, string $propertyPath =
 *     null)
 * @method static array allSatisfy(mixed $value, callable $callback, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static array allScalar(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static string[] allStartsWith(mixed $string, string $needle, string|callable $message = null, string
 *     $propertyPath = null, string $encoding = 'utf8')
 * @method static string[] allString(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static object[] allSubclassOf(mixed $value, string $className, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static true[] allTrue(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static string[] allUrl(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static string[] allUuid(string $value, string|callable $message = null, string $propertyPath = null)
 * @method static string[] allVersion(string $version1, string $operator, string $version2, string|callable $message =
 *     null, string $propertyPath = null)
 * @method static string[] allWriteable(string $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrAlnum(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrBase64(string $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrBetween(mixed $value, mixed $lowerLimit, mixed $upperLimit, string $message = null, string
 *     $propertyPath = null)
 * @method static bool nullOrBetweenExclusive(mixed $value, mixed $lowerLimit, mixed $upperLimit, string $message =
 *     null, string $propertyPath = null)
 * @method static bool nullOrBetweenLength(mixed $value, int $minLength, int $maxLength, string|callable $message =
 *     null, string $propertyPath = null, string $encoding = 'utf8')
 * @method static null|bool nullOrBoolean(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrChoice(mixed $value, array $choices, string|callable $message = null, string $propertyPath
 *     = null)
 * @method static bool nullOrChoicesNotEmpty(array $values, array $choices, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static bool nullOrClassExists(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrContains(mixed $string, string $needle, string|callable $message = null, string
 *     $propertyPath = null, string $encoding = 'utf8')
 * @method static bool nullOrCount(array|\Countable|\ResourceBundle|\SimpleXMLElement $countable, int $count, string
 *     $message = null, string $propertyPath = null)
 * @method static bool nullOrDate(string $value, string $format, string|callable $message = null, string $propertyPath
 *     = null)
 * @method static bool nullOrDefined(mixed $constant, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrDigit(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrDirectory(string $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrE164(string $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrEmail(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrEndsWith(mixed $string, string $needle, string|callable $message = null, string
 *     $propertyPath = null, string $encoding = 'utf8')
 * @method static bool nullOrEq(mixed $value, mixed $value2, string|callable $message = null, string $propertyPath =
 *     null)
 * @method static bool nullOrExtensionLoaded(mixed $value, string|callable $message = null, string $propertyPath =
 *     null)
 * @method static bool nullOrExtensionVersion(string $extension, string $operator, mixed $version, string|callable
 *     $message = null, string $propertyPath = null)
 * @method static bool nullOrFalse(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrFile(string $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrFloat(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrGreaterOrEqualThan(mixed $value, mixed $limit, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static bool nullOrGreaterThan(mixed $value, mixed $limit, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static bool nullOrImplementsInterface(mixed $class, string $interfaceName, string|callable $message = null,
 *     string $propertyPath = null)
 * @method static bool nullOrInArray(mixed $value, array $choices, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static bool nullOrInteger(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrIntegerish(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrInterfaceExists(mixed $value, string|callable $message = null, string $propertyPath =
 *     null)
 * @method static bool nullOrIp(string $value, int $flag = null, string|callable $message = null, string $propertyPath
 *     = null)
 * @method static bool nullOrIpv4(string $value, int $flag = null, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static bool nullOrIpv6(string $value, int $flag = null, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static bool nullOrIsArray(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrIsArrayAccessible(mixed $value, string|callable $message = null, string $propertyPath =
 *     null)
 * @method static bool nullOrIsCallable(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrIsCountable(array|\Countable|\ResourceBundle|\SimpleXMLElement $value, string|callable
 *     $message = null, string $propertyPath = null)
 * @method static bool nullOrIsInstanceOf(mixed $value, string $className, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static bool nullOrIsJsonString(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrIsObject(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrIsResource(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrIsTraversable(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrKeyExists(mixed $value, string|int $key, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static bool nullOrKeyIsset(mixed $value, string|int $key, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static bool nullOrKeyNotExists(mixed $value, string|int $key, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static bool nullOrLength(mixed $value, int $length, string|callable $message = null, string $propertyPath =
 *     null, string $encoding = 'utf8')
 * @method static bool nullOrLessOrEqualThan(mixed $value, mixed $limit, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static bool nullOrLessThan(mixed $value, mixed $limit, string|callable $message = null, string $propertyPath
 *     = null)
 * @method static bool nullOrMax(mixed $value, mixed $maxValue, string|callable $message = null, string $propertyPath =
 *     null)
 * @method static bool nullOrMaxCount(array|\Countable|\ResourceBundle|\SimpleXMLElement $countable, int $count, string
 *     $message = null, string $propertyPath = null)
 * @method static bool nullOrMaxLength(mixed $value, int $maxLength, string|callable $message = null, string
 *     $propertyPath = null, string $encoding = 'utf8')
 * @method static bool nullOrMethodExists(string $value, mixed $object, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static bool nullOrMin(mixed $value, mixed $minValue, string|callable $message = null, string $propertyPath =
 *     null)
 * @method static bool nullOrMinCount(array|\Countable|\ResourceBundle|\SimpleXMLElement $countable, int $count, string
 *     $message = null, string $propertyPath = null)
 * @method static bool nullOrMinLength(mixed $value, int $minLength, string|callable $message = null, string
 *     $propertyPath = null, string $encoding = 'utf8')
 * @method static bool nullOrNoContent(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrNotBlank(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrNotContains(mixed $string, string $needle, string|callable $message = null, string
 *     $propertyPath = null, string $encoding = 'utf8')
 * @method static bool nullOrNotEmpty(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrNotEmptyKey(mixed $value, string|int $key, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static bool nullOrNotEq(mixed $value1, mixed $value2, string|callable $message = null, string $propertyPath
 *     = null)
 * @method static bool nullOrNotInArray(mixed $value, array $choices, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static bool nullOrNotIsInstanceOf(mixed $value, string $className, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static bool nullOrNotNull(mixed $value, string|callable $message = null, string $propertyPath = null) Assert
 * @method static bool nullOrNotRegex(mixed $value, string $pattern, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static bool nullOrNotSame(mixed $value1, mixed $value2, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static bool nullOrNumeric(mixed $value, string|callable $message = null, string $propertyPath = null) Assert
 * @method static bool nullOrObjectOrClass(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static null|string nullOrPhpVersion(string $operator, mixed $version, string|callable $message = null,
 *     string $propertyPath = null)
 * @method static null|string nullOrPropertiesExist(mixed $value, array $properties, string|callable $message = null,
 *     string $propertyPath = null)
 * @method static null|string nullOrPropertyExists(mixed $value, string $property, string|callable $message = null,
 *     string $propertyPath = null)
 * @method static bool nullOrRange(mixed $value, mixed $minValue, mixed $maxValue, string|callable $message = null,
 *     string $propertyPath = null)
 * @method static null|string nullOrReadable(string $value, string|callable $message = null, string $propertyPath =
 *     null)
 * @method static bool nullOrRegex(mixed $value, string $pattern, string|callable $message = null, string $propertyPath
 *     = null)
 * @method static bool nullOrSame(mixed $value, mixed $value2, string|callable $message = null, string $propertyPath =
 *     null)
 * @method static bool nullOrSatisfy(mixed $value, callable $callback, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static bool nullOrScalar(mixed $value, string|callable $message = null, string $propertyPath = null) Assert
 * @method static null|string nullOrStartsWith(mixed $string, string $needle, string|callable $message = null, string
 *     $propertyPath = null, string $encoding = 'utf8')
 * @method static null|string nullOrString(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static bool nullOrSubclassOf(mixed $value, string $className, string|callable $message = null, string
 *     $propertyPath = null)
 * @method static bool nullOrTrue(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static null|string nullOrUrl(mixed $value, string|callable $message = null, string $propertyPath = null)
 * @method static null|string nullOrUuid(string $value, string|callable $message = null, string $propertyPath = null)
 * @method static null|string nullOrVersion(string $version1, string $operator, string $version2, string|callable
 *     $message = null, string $propertyPath = null)
 * @method static null|string nullOrWriteable(string $value, string|callable $message = null, string $propertyPath =
 *     null)
 */
class Assert
{
    public static function __callStatic(string $method, array $arguments)
    {
        AssertRules::{$method}(...$arguments);

        // Return passed in value.
        return array_shift($arguments);
    }
}