<?php

declare(strict_types = 1);

namespace Tool\Validation;

/**
 * Class IsValid
 *
 * Call assertion rule, return true if valud, false if not.
 *
 * @method static bool equals($value, $value2, $message = null, $propertyPath = null)
 * @method static bool eq($value, $value2, $message = null, $propertyPath = null)
 * @method static bool same($value, $value2, $message = null, $propertyPath = null)
 * @method static bool notEq($value1, $value2, $message = null, $propertyPath = null)
 * @method static bool notSame($value1, $value2, $message = null, $propertyPath = null)
 * @method static bool notInArray($value, array $choices, $message = null, $propertyPath = null)
 * @method static bool integer($value, $message = null, $propertyPath = null)
 * @method static bool float($value, $message = null, $propertyPath = null)
 * @method static bool digit($value, $message = null, $propertyPath = null)
 * @method static bool integerish($value, $message = null, $propertyPath = null)
 * @method static bool boolean($value, $message = null, $propertyPath = null)
 * @method static bool scalar($value, $message = null, $propertyPath = null)
 * @method static bool notEmpty($value, $message = null, $propertyPath = null)
 * @method static bool noContent($value, $message = null, $propertyPath = null)
 * @method static bool null($value, $message = null, $propertyPath = null)
 * @method static bool notNull($value, $message = null, $propertyPath = null)
 * @method static bool string($value, $message = null, $propertyPath = null)
 * @method static bool regex($value, $pattern, $message = null, $propertyPath = null)
 * @method static bool notRegex($value, $pattern, $message = null, $propertyPath = null)
 * @method static bool length($value, $length, $message = null, $propertyPath = null, $encoding = 'utf8')
 * @method static bool minLength($value, $minLength, $message = null, $propertyPath = null, $encoding = 'utf8')
 * @method static bool maxLength($value, $maxLength, $message = null, $propertyPath = null, $encoding = 'utf8')
 * @method static bool betweenLength($value, $minLength, $maxLength, $message = null, $propertyPath = null, $encoding = 'utf8')
 * @method static bool startsWith($string, $needle, $message = null, $propertyPath = null, $encoding = 'utf8')
 * @method static bool endsWith($string, $needle, $message = null, $propertyPath = null, $encoding = 'utf8')
 * @method static bool contains($string, $needle, $message = null, $propertyPath = null, $encoding = 'utf8')
 * @method static bool notContains($string, $needle, $message = null, $propertyPath = null, $encoding = 'utf8')
 * @method static bool choice($value, array $choices, $message = null, $propertyPath = null)
 * @method static bool inArray($value, array $choices, $message = null, $propertyPath = null)
 * @method static bool numeric($value, $message = null, $propertyPath = null)
 * @method static bool isResource($value, $message = null, $propertyPath = null)
 * @method static bool isArray($value, $message = null, $propertyPath = null)
 * @method static bool isTraversable($value, $message = null, $propertyPath = null)
 * @method static bool isArrayAccessible($value, $message = null, $propertyPath = null)
 * @method static bool isCountable($value, $message = null, $propertyPath = null)
 * @method static bool keyExists($value, $key, $message = null, $propertyPath = null)
 * @method static bool keyNotExists($value, $key, $message = null, $propertyPath = null)
 * @method static bool keyIsset($value, $key, $message = null, $propertyPath = null)
 * @method static bool notEmptyKey($value, $key, $message = null, $propertyPath = null)
 * @method static bool notBlank($value, $message = null, $propertyPath = null)
 * @method static bool isInstanceOf($value, $className, $message = null, $propertyPath = null)
 * @method static bool notIsInstanceOf($value, $className, $message = null, $propertyPath = null)
 * @method static bool subclassOf($value, $className, $message = null, $propertyPath = null)
 * @method static bool range($value, $minValue, $maxValue, $message = null, $propertyPath = null)
 * @method static bool min($value, $minValue, $message = null, $propertyPath = null)
 * @method static bool max($value, $maxValue, $message = null, $propertyPath = null)
 * @method static bool file($value, $message = null, $propertyPath = null)
 * @method static bool directory($value, $message = null, $propertyPath = null)
 * @method static bool readable($value, $message = null, $propertyPath = null)
 * @method static bool writeable($value, $message = null, $propertyPath = null)
 * @method static bool email($value, $message = null, $propertyPath = null)
 * @method static bool url($value, $message = null, $propertyPath = null)
 * @method static bool alnum($value, $message = null, $propertyPath = null)
 * @method static bool true($value, $message = null, $propertyPath = null)
 * @method static bool false($value, $message = null, $propertyPath = null)
 * @method static bool classExists($value, $message = null, $propertyPath = null)
 * @method static bool interfaceExists($value, $message = null, $propertyPath = null)
 * @method static bool implementsInterface($class, $interfaceName, $message = null, $propertyPath = null)
 * @method static bool isJsonString($value, $message = null, $propertyPath = null)
 * @method static bool uuid($value, $message = null, $propertyPath = null)
 * @method static bool e164($value, $message = null, $propertyPath = null)
 * @method static bool count($countable, $count, $message = null, $propertyPath = null)
 * @method static bool minCount($countable, $count, $message = null, $propertyPath = null)
 * @method static bool maxCount($countable, $count, $message = null, $propertyPath = null)
 * @method static bool choicesNotEmpty(array $values, array $choices, $message = null, $propertyPath = null)
 * @method static bool methodExists($value, $object, $message = null, $propertyPath = null)
 * @method static bool isObject($value, $message = null, $propertyPath = null)
 * @method static bool lessThan($value, $limit, $message = null, $propertyPath = null)
 * @method static bool lessOrEqualThan($value, $limit, $message = null, $propertyPath = null)
 * @method static bool greaterThan($value, $limit, $message = null, $propertyPath = null)
 * @method static bool greaterOrEqualThan($value, $limit, $message = null, $propertyPath = null)
 * @method static bool between($value, $lowerLimit, $upperLimit, $message = null, $propertyPath = null)
 * @method static bool betweenExclusive($value, $lowerLimit, $upperLimit, $message = null, $propertyPath = null)
 * @method static bool extensionLoaded($value, $message = null, $propertyPath = null)
 * @method static bool date($value, $format, $message = null, $propertyPath = null)
 * @method static bool objectOrClass($value, $message = null, $propertyPath = null)
 * @method static bool propertyExists($value, $property, $message = null, $propertyPath = null)
 * @method static bool propertiesExist($value, array $properties, $message = null, $propertyPath = null)
 * @method static bool version($version1, $operator, $version2, $message = null, $propertyPath = null)
 * @method static bool phpVersion($operator, $version, $message = null, $propertyPath = null)
 * @method static bool extensionVersion($extension, $operator, $version, $message = null, $propertyPath = null)
 * @method static bool isCallable($value, $message = null, $propertyPath = null)
 * @method static bool satisfy($value, $callback, $message = null, $propertyPath = null)
 * @method static bool ip($value, $flag = null, $message = null, $propertyPath = null)
 * @method static bool ipv4($value, $flag = null, $message = null, $propertyPath = null)
 * @method static bool ipv6($value, $flag = null, $message = null, $propertyPath = null)
 * @method static bool defined($constant, $message = null, $propertyPath = null)
 * @method static bool base64($value, $message = null, $propertyPath = null)
 * @method static bool allAlnum(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is alphanumeric for all values.
 * @method static bool allBase64(string $value, string|callable $message = null, string $propertyPath = null) Assert that a constant is defined for all values.
 * @method static bool allBetween(mixed $value, mixed $lowerLimit, mixed $upperLimit, string $message = null, string $propertyPath = null) Assert that a value is greater or equal than a lower limit, and less than or equal to an upper limit for all values.
 * @method static bool allBetweenExclusive(mixed $value, mixed $lowerLimit, mixed $upperLimit, string $message = null, string $propertyPath = null) Assert that a value is greater than a lower limit, and less than an upper limit for all values.
 * @method static bool allBetweenLength(mixed $value, int $minLength, int $maxLength, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string length is between min and max lengths for all values.
 * @method static bool allBoolean(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is php boolean for all values.
 * @method static bool allChoice(mixed $value, array $choices, string|callable $message = null, string $propertyPath = null) Assert that value is in array of choices for all values.
 * @method static bool allChoicesNotEmpty(array $values, array $choices, string|callable $message = null, string $propertyPath = null) Determines if the values array has every choice as key and that this choice has content for all values.
 * @method static bool allClassExists(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that the class exists for all values.
 * @method static bool allContains(mixed $string, string $needle, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string contains a sequence of chars for all values.
 * @method static bool allCount(array|\Countable|\ResourceBundle|\SimpleXMLElement $countable, int $count, string $message = null, string $propertyPath = null) Assert that the count of countable is equal to count for all values.
 * @method static bool allDate(string $value, string $format, string|callable $message = null, string $propertyPath = null) Assert that date is valid and corresponds to the given format for all values.
 * @method static bool allDefined(mixed $constant, string|callable $message = null, string $propertyPath = null) Assert that a constant is defined for all values.
 * @method static bool allDigit(mixed $value, string|callable $message = null, string $propertyPath = null) Validates if an integer or integerish is a digit for all values.
 * @method static bool allDirectory(string $value, string|callable $message = null, string $propertyPath = null) Assert that a directory exists for all values.
 * @method static bool allE164(string $value, string|callable $message = null, string $propertyPath = null) Assert that the given string is a valid E164 Phone Number for all values.
 * @method static bool allEmail(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is an email address (using input_filter/FILTER_VALIDATE_EMAIL) for all values.
 * @method static bool allEndsWith(mixed $string, string $needle, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string ends with a sequence of chars for all values.
 * @method static bool allEq(mixed $value, mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are equal (using ==) for all values.
 * @method static bool allExtensionLoaded(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that extension is loaded for all values.
 * @method static bool allExtensionVersion(string $extension, string $operator, mixed $version, string|callable $message = null, string $propertyPath = null) Assert that extension is loaded and a specific version is installed for all values.
 * @method static bool allFalse(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that the value is boolean False for all values.
 * @method static bool allFile(string $value, string|callable $message = null, string $propertyPath = null) Assert that a file exists for all values.
 * @method static bool allFloat(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is a php float for all values.
 * @method static bool allGreaterOrEqualThan(mixed $value, mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is greater or equal than given limit for all values.
 * @method static bool allGreaterThan(mixed $value, mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is greater than given limit for all values.
 * @method static bool allImplementsInterface(mixed $class, string $interfaceName, string|callable $message = null, string $propertyPath = null) Assert that the class implements the interface for all values.
 * @method static bool allInArray(mixed $value, array $choices, string|callable $message = null, string $propertyPath = null) Assert that value is in array of choices. This is an alias of Assertion::choice() for all values.
 * @method static bool allInteger(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is a php integer for all values.
 * @method static bool allIntegerish(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is a php integer'ish for all values.
 * @method static bool allInterfaceExists(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that the interface exists for all values.
 * @method static bool allIp(string $value, int $flag = null, string|callable $message = null, string $propertyPath = null) Assert that value is an IPv4 or IPv6 address for all values.
 * @method static bool allIpv4(string $value, int $flag = null, string|callable $message = null, string $propertyPath = null) Assert that value is an IPv4 address for all values.
 * @method static bool allIpv6(string $value, int $flag = null, string|callable $message = null, string $propertyPath = null) Assert that value is an IPv6 address for all values.
 * @method static bool allIsArray(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is an array for all values.
 * @method static bool allIsArrayAccessible(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is an array or an array-accessible object for all values.
 * @method static bool allIsCallable(mixed $value, string|callable $message = null, string $propertyPath = null) Determines that the provided value is callable for all values.
 * @method static bool allIsCountable(array|\Countable|\ResourceBundle|\SimpleXMLElement $value, string|callable $message = null, string $propertyPath = null) Assert that value is countable for all values.
 * @method static bool allIsInstanceOf(mixed $value, string $className, string|callable $message = null, string $propertyPath = null) Assert that value is instance of given class-name for all values.
 * @method static bool allIsJsonString(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that the given string is a valid json string for all values.
 * @method static bool allIsObject(mixed $value, string|callable $message = null, string $propertyPath = null) Determines that the provided value is an object for all values.
 * @method static bool allIsResource(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is a resource for all values.
 * @method static bool allIsTraversable(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is an array or a traversable object for all values.
 * @method static bool allKeyExists(mixed $value, string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key exists in an array for all values.
 * @method static bool allKeyIsset(mixed $value, string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key exists in an array/array-accessible object using isset() for all values.
 * @method static bool allKeyNotExists(mixed $value, string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key does not exist in an array for all values.
 * @method static bool allLength(mixed $value, int $length, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string has a given length for all values.
 * @method static bool allLessOrEqualThan(mixed $value, mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is less or equal than given limit for all values.
 * @method static bool allLessThan(mixed $value, mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is less than given limit for all values.
 * @method static bool allMax(mixed $value, mixed $maxValue, string|callable $message = null, string $propertyPath = null) Assert that a number is smaller as a given limit for all values.
 * @method static bool allMaxCount(array|\Countable|\ResourceBundle|\SimpleXMLElement $countable, int $count, string $message = null, string $propertyPath = null) Assert that the countable have at most $count elements for all values.
 * @method static bool allMaxLength(mixed $value, int $maxLength, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string value is not longer than $maxLength chars for all values.
 * @method static bool allMethodExists(string $value, mixed $object, string|callable $message = null, string $propertyPath = null) Determines that the named method is defined in the provided object for all values.
 * @method static bool allMin(mixed $value, mixed $minValue, string|callable $message = null, string $propertyPath = null) Assert that a value is at least as big as a given limit for all values.
 * @method static bool allMinCount(array|\Countable|\ResourceBundle|\SimpleXMLElement $countable, int $count, string $message = null, string $propertyPath = null) Assert that the countable have at least $count elements for all values.
 * @method static bool allMinLength(mixed $value, int $minLength, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that a string is at least $minLength chars long for all values.
 * @method static bool allNoContent(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is empty for all values.
 * @method static bool allNotBlank(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is not blank for all values.
 * @method static bool allNotContains(mixed $string, string $needle, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string does not contains a sequence of chars for all values.
 * @method static bool allNotEmpty(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is not empty for all values.
 * @method static bool allNotEmptyKey(mixed $value, string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key exists in an array/array-accessible object and its value is not empty for all values.
 * @method static bool allNotEq(mixed $value1, mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are not equal (using == ) for all values.
 * @method static bool allNotInArray(mixed $value, array $choices, string|callable $message = null, string $propertyPath = null) Assert that value is not in array of choices for all values.
 * @method static bool allNotIsInstanceOf(mixed $value, string $className, string|callable $message = null, string $propertyPath = null) Assert that value is not instance of given class-name for all values.
 * @method static bool allNotNull(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is not null for all values.
 * @method static bool allNotRegex(mixed $value, string $pattern, string|callable $message = null, string $propertyPath = null) Assert that value does not match a regex for all values.
 * @method static bool allNotSame(mixed $value1, mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are not the same (using === ) for all values.
 * @method static bool allNull(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is null for all values.
 * @method static bool allNumeric(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is numeric for all values.
 * @method static bool allObjectOrClass(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that the value is an object, or a class that exists for all values.
 * @method static bool allPhpVersion(string $operator, mixed $version, string|callable $message = null, string $propertyPath = null) Assert on PHP version for all values.
 * @method static bool allPropertiesExist(mixed $value, array $properties, string|callable $message = null, string $propertyPath = null) Assert that the value is an object or class, and that the properties all exist for all values.
 * @method static bool allPropertyExists(mixed $value, string $property, string|callable $message = null, string $propertyPath = null) Assert that the value is an object or class, and that the property exists for all values.
 * @method static bool allRange(mixed $value, mixed $minValue, mixed $maxValue, string|callable $message = null, string $propertyPath = null) Assert that value is in range of numbers for all values.
 * @method static bool allReadable(string $value, string|callable $message = null, string $propertyPath = null) Assert that the value is something readable for all values.
 * @method static bool allRegex(mixed $value, string $pattern, string|callable $message = null, string $propertyPath = null) Assert that value matches a regex for all values.
 * @method static bool allSame(mixed $value, mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are the same (using ===) for all values.
 * @method static bool allSatisfy(mixed $value, callable $callback, string|callable $message = null, string $propertyPath = null) Assert that the provided value is valid according to a callback for all values.
 * @method static bool allScalar(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is a PHP scalar for all values.
 * @method static bool allStartsWith(mixed $string, string $needle, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string starts with a sequence of chars for all values.
 * @method static bool allString(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is a string for all values.
 * @method static bool allSubclassOf(mixed $value, string $className, string|callable $message = null, string $propertyPath = null) Assert that value is subclass of given class-name for all values.
 * @method static bool allTrue(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that the value is boolean True for all values.
 * @method static bool allUrl(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is an URL for all values.
 * @method static bool allUuid(string $value, string|callable $message = null, string $propertyPath = null) Assert that the given string is a valid UUID for all values.
 * @method static bool allVersion(string $version1, string $operator, string $version2, string|callable $message = null, string $propertyPath = null) Assert comparison of two versions for all values.
 * @method static bool allWriteable(string $value, string|callable $message = null, string $propertyPath = null) Assert that the value is something writeable for all values.
 * @method static bool nullOrAlnum(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is alphanumeric or that the value is null.
 * @method static bool nullOrBase64(string $value, string|callable $message = null, string $propertyPath = null) Assert that a constant is defined or that the value is null.
 * @method static bool nullOrBetween(mixed $value, mixed $lowerLimit, mixed $upperLimit, string $message = null, string $propertyPath = null) Assert that a value is greater or equal than a lower limit, and less than or equal to an upper limit or that the value is null.
 * @method static bool nullOrBetweenExclusive(mixed $value, mixed $lowerLimit, mixed $upperLimit, string $message = null, string $propertyPath = null) Assert that a value is greater than a lower limit, and less than an upper limit or that the value is null.
 * @method static bool nullOrBetweenLength(mixed $value, int $minLength, int $maxLength, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string length is between min and max lengths or that the value is null.
 * @method static bool nullOrBoolean(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is php boolean or that the value is null.
 * @method static bool nullOrChoice(mixed $value, array $choices, string|callable $message = null, string $propertyPath = null) Assert that value is in array of choices or that the value is null.
 * @method static bool nullOrChoicesNotEmpty(array $values, array $choices, string|callable $message = null, string $propertyPath = null) Determines if the values array has every choice as key and that this choice has content or that the value is null.
 * @method static bool nullOrClassExists(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that the class exists or that the value is null.
 * @method static bool nullOrContains(mixed $string, string $needle, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string contains a sequence of chars or that the value is null.
 * @method static bool nullOrCount(array|\Countable|\ResourceBundle|\SimpleXMLElement $countable, int $count, string $message = null, string $propertyPath = null) Assert that the count of countable is equal to count or that the value is null.
 * @method static bool nullOrDate(string $value, string $format, string|callable $message = null, string $propertyPath = null) Assert that date is valid and corresponds to the given format or that the value is null.
 * @method static bool nullOrDefined(mixed $constant, string|callable $message = null, string $propertyPath = null) Assert that a constant is defined or that the value is null.
 * @method static bool nullOrDigit(mixed $value, string|callable $message = null, string $propertyPath = null) Validates if an integer or integerish is a digit or that the value is null.
 * @method static bool nullOrDirectory(string $value, string|callable $message = null, string $propertyPath = null) Assert that a directory exists or that the value is null.
 * @method static bool nullOrE164(string $value, string|callable $message = null, string $propertyPath = null) Assert that the given string is a valid E164 Phone Number or that the value is null.
 * @method static bool nullOrEmail(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is an email address (using input_filter/FILTER_VALIDATE_EMAIL) or that the value is null.
 * @method static bool nullOrEndsWith(mixed $string, string $needle, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string ends with a sequence of chars or that the value is null.
 * @method static bool nullOrEq(mixed $value, mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are equal (using ==) or that the value is null.
 * @method static bool nullOrExtensionLoaded(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that extension is loaded or that the value is null.
 * @method static bool nullOrExtensionVersion(string $extension, string $operator, mixed $version, string|callable $message = null, string $propertyPath = null) Assert that extension is loaded and a specific version is installed or that the value is null.
 * @method static bool nullOrFalse(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that the value is boolean False or that the value is null.
 * @method static bool nullOrFile(string $value, string|callable $message = null, string $propertyPath = null) Assert that a file exists or that the value is null.
 * @method static bool nullOrFloat(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is a php float or that the value is null.
 * @method static bool nullOrGreaterOrEqualThan(mixed $value, mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is greater or equal than given limit or that the value is null.
 * @method static bool nullOrGreaterThan(mixed $value, mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is greater than given limit or that the value is null.
 * @method static bool nullOrImplementsInterface(mixed $class, string $interfaceName, string|callable $message = null, string $propertyPath = null) Assert that the class implements the interface or that the value is null.
 * @method static bool nullOrInArray(mixed $value, array $choices, string|callable $message = null, string $propertyPath = null) Assert that value is in array of choices. This is an alias of Assertion::choice() or that the value is null.
 * @method static bool nullOrInteger(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is a php integer or that the value is null.
 * @method static bool nullOrIntegerish(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is a php integer'ish or that the value is null.
 * @method static bool nullOrInterfaceExists(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that the interface exists or that the value is null.
 * @method static bool nullOrIp(string $value, int $flag = null, string|callable $message = null, string $propertyPath = null) Assert that value is an IPv4 or IPv6 address or that the value is null.
 * @method static bool nullOrIpv4(string $value, int $flag = null, string|callable $message = null, string $propertyPath = null) Assert that value is an IPv4 address or that the value is null.
 * @method static bool nullOrIpv6(string $value, int $flag = null, string|callable $message = null, string $propertyPath = null) Assert that value is an IPv6 address or that the value is null.
 * @method static bool nullOrIsArray(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is an array or that the value is null.
 * @method static bool nullOrIsArrayAccessible(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is an array or an array-accessible object or that the value is null.
 * @method static bool nullOrIsCallable(mixed $value, string|callable $message = null, string $propertyPath = null) Determines that the provided value is callable or that the value is null.
 * @method static bool nullOrIsCountable(array|\Countable|\ResourceBundle|\SimpleXMLElement $value, string|callable $message = null, string $propertyPath = null) Assert that value is countable or that the value is null.
 * @method static bool nullOrIsInstanceOf(mixed $value, string $className, string|callable $message = null, string $propertyPath = null) Assert that value is instance of given class-name or that the value is null.
 * @method static bool nullOrIsJsonString(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that the given string is a valid json string or that the value is null.
 * @method static bool nullOrIsObject(mixed $value, string|callable $message = null, string $propertyPath = null) Determines that the provided value is an object or that the value is null.
 * @method static bool nullOrIsResource(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is a resource or that the value is null.
 * @method static bool nullOrIsTraversable(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is an array or a traversable object or that the value is null.
 * @method static bool nullOrKeyExists(mixed $value, string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key exists in an array or that the value is null.
 * @method static bool nullOrKeyIsset(mixed $value, string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key exists in an array/array-accessible object using isset() or that the value is null.
 * @method static bool nullOrKeyNotExists(mixed $value, string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key does not exist in an array or that the value is null.
 * @method static bool nullOrLength(mixed $value, int $length, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string has a given length or that the value is null.
 * @method static bool nullOrLessOrEqualThan(mixed $value, mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is less or equal than given limit or that the value is null.
 * @method static bool nullOrLessThan(mixed $value, mixed $limit, string|callable $message = null, string $propertyPath = null) Determines if the value is less than given limit or that the value is null.
 * @method static bool nullOrMax(mixed $value, mixed $maxValue, string|callable $message = null, string $propertyPath = null) Assert that a number is smaller as a given limit or that the value is null.
 * @method static bool nullOrMaxCount(array|\Countable|\ResourceBundle|\SimpleXMLElement $countable, int $count, string $message = null, string $propertyPath = null) Assert that the countable have at most $count elements or that the value is null.
 * @method static bool nullOrMaxLength(mixed $value, int $maxLength, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string value is not longer than $maxLength chars or that the value is null.
 * @method static bool nullOrMethodExists(string $value, mixed $object, string|callable $message = null, string $propertyPath = null) Determines that the named method is defined in the provided object or that the value is null.
 * @method static bool nullOrMin(mixed $value, mixed $minValue, string|callable $message = null, string $propertyPath = null) Assert that a value is at least as big as a given limit or that the value is null.
 * @method static bool nullOrMinCount(array|\Countable|\ResourceBundle|\SimpleXMLElement $countable, int $count, string $message = null, string $propertyPath = null) Assert that the countable have at least $count elements or that the value is null.
 * @method static bool nullOrMinLength(mixed $value, int $minLength, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that a string is at least $minLength chars long or that the value is null.
 * @method static bool nullOrNoContent(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is empty or that the value is null.
 * @method static bool nullOrNotBlank(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is not blank or that the value is null.
 * @method static bool nullOrNotContains(mixed $string, string $needle, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string does not contains a sequence of chars or that the value is null.
 * @method static bool nullOrNotEmpty(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is not empty or that the value is null.
 * @method static bool nullOrNotEmptyKey(mixed $value, string|int $key, string|callable $message = null, string $propertyPath = null) Assert that key exists in an array/array-accessible object and its value is not empty or that the value is null.
 * @method static bool nullOrNotEq(mixed $value1, mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are not equal (using == ) or that the value is null.
 * @method static bool nullOrNotInArray(mixed $value, array $choices, string|callable $message = null, string $propertyPath = null) Assert that value is not in array of choices or that the value is null.
 * @method static bool nullOrNotIsInstanceOf(mixed $value, string $className, string|callable $message = null, string $propertyPath = null) Assert that value is not instance of given class-name or that the value is null.
 * @method static bool nullOrNotNull(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is not null or that the value is null.
 * @method static bool nullOrNotRegex(mixed $value, string $pattern, string|callable $message = null, string $propertyPath = null) Assert that value does not match a regex or that the value is null.
 * @method static bool nullOrNotSame(mixed $value1, mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are not the same (using === ) or that the value is null.
 * @method static bool nullOrNull(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is null or that the value is null.
 * @method static bool nullOrNumeric(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is numeric or that the value is null.
 * @method static bool nullOrObjectOrClass(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that the value is an object, or a class that exists or that the value is null.
 * @method static bool nullOrPhpVersion(string $operator, mixed $version, string|callable $message = null, string $propertyPath = null) Assert on PHP version or that the value is null.
 * @method static bool nullOrPropertiesExist(mixed $value, array $properties, string|callable $message = null, string $propertyPath = null) Assert that the value is an object or class, and that the properties all exist or that the value is null.
 * @method static bool nullOrPropertyExists(mixed $value, string $property, string|callable $message = null, string $propertyPath = null) Assert that the value is an object or class, and that the property exists or that the value is null.
 * @method static bool nullOrRange(mixed $value, mixed $minValue, mixed $maxValue, string|callable $message = null, string $propertyPath = null) Assert that value is in range of numbers or that the value is null.
 * @method static bool nullOrReadable(string $value, string|callable $message = null, string $propertyPath = null) Assert that the value is something readable or that the value is null.
 * @method static bool nullOrRegex(mixed $value, string $pattern, string|callable $message = null, string $propertyPath = null) Assert that value matches a regex or that the value is null.
 * @method static bool nullOrSame(mixed $value, mixed $value2, string|callable $message = null, string $propertyPath = null) Assert that two values are the same (using ===) or that the value is null.
 * @method static bool nullOrSatisfy(mixed $value, callable $callback, string|callable $message = null, string $propertyPath = null) Assert that the provided value is valid according to a callback or that the value is null.
 * @method static bool nullOrScalar(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is a PHP scalar or that the value is null.
 * @method static bool nullOrStartsWith(mixed $string, string $needle, string|callable $message = null, string $propertyPath = null, string $encoding = 'utf8') Assert that string starts with a sequence of chars or that the value is null.
 * @method static bool nullOrString(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is a string or that the value is null.
 * @method static bool nullOrSubclassOf(mixed $value, string $className, string|callable $message = null, string $propertyPath = null) Assert that value is subclass of given class-name or that the value is null.
 * @method static bool nullOrTrue(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that the value is boolean True or that the value is null.
 * @method static bool nullOrUrl(mixed $value, string|callable $message = null, string $propertyPath = null) Assert that value is an URL or that the value is null.
 * @method static bool nullOrUuid(string $value, string|callable $message = null, string $propertyPath = null) Assert that the given string is a valid UUID or that the value is null.
 * @method static bool nullOrVersion(string $version1, string $operator, string $version2, string|callable $message = null, string $propertyPath = null) Assert comparison of two versions or that the value is null.
 * @method static bool nullOrWriteable(string $value, string|callable $message = null, string $propertyPath = null) Assert that the value is something writeable or that the value is null.
 */
class IsValid
{
    public static function __callStatic(string $method, array $arguments)
    {
        try {
            AssertRules::{$method}(...$arguments);
        }
        catch (\InvalidArgumentException $exception) {
            return false;
        }

        return true;
    }
}