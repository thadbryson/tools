<?php

declare(strict_types = 1);

namespace Tool;

use Tool\Validation\Assert;

/**
 * Class StrStatic
 *
 * Call methods on Tool\Str class statically.
 *
 * @method static string append(string $str, string $stringAppend, string $encoding = null)
 * @method static string at(string $str, int $index, string $encoding = null)
 * @method static string between(string $str, string $start, string $end, int $offset = 0, string $encoding = null)
 * @method static string camelize(string $str, string $encoding = null)
 * @method static string chars(string $str, string $encoding = null)
 * @method static string collapseWhitespace(string $str, string $encoding = null)
 * @method static bool contains(string $str, string $needle, bool $caseSensitive = true, string $encoding = null)
 * @method static bool containsAll(string $str, string[] $needle, bool $caseSensitive = true, string $encoding = null)
 * @method static bool containsAny(string $str, string[] $needle, bool $caseSensitive = true, string $encoding = null)
 * @method static int count(string $str, string $encoding = null)
 * @method static int countSubstr(string $str, string $substring, bool $caseSensitive = true, string $encoding = null)
 * @method static string dasherize(string $str, string $encoding = null)
 * @method static string delimit(string $str, string $delimiter, string $encoding = null)
 * @method static bool endsWith(string $str, string $substring, bool $caseSensitive = true, string $encoding = null)
 * @method static bool endsWithAny(string $str, string[] $substrings, bool $caseSensitive = true, string $encoding = null)
 * @method static string ensureLeft(string $str, string $substring, string $encoding = null)
 * @method static string ensureRight(string $str, string $substring, string $encoding = null)
 * @method static string first(string $str, int $n, string $encoding = null)
 * @method static bool hasLowerCase(string $str, string $encoding = null)
 * @method static bool hasUpperCase(string $str, string $encoding = null)
 * @method static string htmlDecode(string $str, int $flags = ENT_COMPAT, string $encoding = null)
 * @method static string htmlEncode(string $str, int $flags = ENT_COMPAT, string $encoding = null)
 * @method static string humanize(string $str, string $encoding = null)
 * @method static int indexOf(string $str, string $needle, int $offset = 0, string $encoding = null)
 * @method static int indexOfLast(string $str, string $needle, int $offset = 0, string $encoding = null)
 * @method static string insert(string $str, string $substring, int $index = 0, string $encoding = null)
 * @method static bool isAlpha(string $str, string $encoding = null)
 * @method static bool isAlphanumeric(string $str, string $encoding = null)
 * @method static bool isBase64(string $str, string $encoding = null)
 * @method static bool isBlank(string $str, string $encoding = null)
 * @method static bool isHexadecimal(string $str, string $encoding = null)
 * @method static bool isJson(string $str, string $encoding = null)
 * @method static bool isLowerCase(string $str, string $encoding = null)
 * @method static bool isSerialized(string $str, string $encoding = null)
 * @method static bool isUpperCase(string $str, string $encoding = null)
 * @method static string last(string $str, string $encoding = null)
 * @method static int length(string $str, string $encoding = null)
 * @method static string[] lines(string $str, string $encoding = null)
 * @method static string longestCommonPrefix(string $str, string $otherStr, string $encoding = null)
 * @method static string longestCommonSuffix(string $str, string $otherStr, string $encoding = null)
 * @method static string longestCommonSubstring(string $str, string $otherStr, string $encoding = null)
 * @method static string lowerCaseFirst(string $str, string $encoding = null)
 * @method static string pad(string $str, int $length, string $padStr = ' ', string $padType = 'right', string $encoding = null)
 * @method static string padBoth(string $str, int $length, string $padStr = ' ', string $encoding = null)
 * @method static string padLeft(string $str, int $length, string $padStr = ' ', string $encoding = null)
 * @method static string padRight(string $str, int $length, string $padStr = ' ', string $encoding = null)
 * @method static string prepend(string $str, string $string, string $encoding = null)
 * @method static string regexReplace(string $str, string $pattern, string $replacement, string $options = 'msr', string $encoding = null)
 * @method static string removeLeft(string $str, string $substring, string $encoding = null)
 * @method static string removeRight(string $str, string $substring, string $encoding = null)
 * @method static string repeat(string $str, int $multiplier, string $encoding = null)
 * @method static string replace(string $str, string $search, string $replacement, string $encoding = null)
 * @method static string reverse(string $str, string $encoding = null)
 * @method static string safeTruncate(string $str, int $length, string $substring = '', string $encoding = null)
 * @method static string shuffle(string $str, string $encoding = null)
 * @method static string slugify(string $str, string $replacement = '-', string $encoding = null)
 * @method static string slice(string $str, int $start, int $end = null, string $encoding = null)
 * @method static string split(string $str, string $pattern, int $limit = null, string $encoding = null)
 * @method static bool startsWith(string $str, string $substring, bool $caseSensitive = true, string $encoding = null)
 * @method static bool startsWithAny(string $str, string[] $substrings, bool $caseSensitive = true, string $encoding = null)
 * @method static string stripWhitespace(string $str, string $encoding = null)
 * @method static string substr(string $str, int $start, int $length = null, string $encoding = null)
 * @method static string surround(string $str, string $substring, string $encoding = null)
 * @method static string swapCase(string $str, string $encoding = null)
 * @method static string tidy(string $str, string $encoding = null)
 * @method static string titleize(string $str, string $encoding = null)
 * @method static string toAscii(string $str, string $language = 'en', bool $removeUnsupported = true, string $encoding = null)
 * @method static bool toBoolean(string $str, string $encoding = null)
 * @method static string toLowerCase(string $str, string $encoding = null)
 * @method static string toSpaces(string $str, int $tabLength = 4, string $encoding = null)
 * @method static string toTabs(string $str, int $tabLength = 4, string $encoding = null)
 * @method static string toTitleCase(string $str, string $encoding = null)
 * @method static string toUpperCase(string $str, string $encoding = null)
 * @method static string trim(string $str, string $chars = null, string $encoding = null)
 * @method static string trimLeft(string $str, string $chars = null, string $encoding = null)
 * @method static string trimRight(string $str, string $chars = null, string $encoding = null)
 * @method static string truncate(string $str, int $length, string $substring = '', string $encoding = null)
 * @method static string underscored(string $str, string $encoding = null)
 * @method static string upperCamelize(string $str, string $encoding = null)
 * @method static string upperCaseFirst(string $str, string $encoding = null)
 * @method static string[] explode(string $str, string $delimiter, string $encoding = null)
 * @method static string beforeSubstr(string $str, string $substr, int $offset = 0, string $encoding = null)
 * @method static string afterSubstr(string $str, string $substr, int $offset = 0, string $encoding = null)
 * @method static mixed jsonDecode(string $str, string $encoding = null)
 * @method static mixed jsonDecodeOptions(string $str, bool $assoc = false, int $options = 0, int $depth = 512, string $encoding = null)
 * @method static string plural(string $str, int $count = 2, string $encoding = null)
 * @method static string getter(string $str, string $append = '', string $encoding = null)
 * @method static string setter(string $str, string $append = '', string $encoding = null)
 * @method static string hasser(string $str, string $append = '', string $encoding = null)
 * @method static string isser(string $str, string $append = '', string $encoding = null)
 * @method static string money(string $str, string $locale = 'en_US', string $format = '%n', string $encoding = null)
 * @method static string moneyInternational(string $str, string $locale = 'en_US', string $encoding = null)
 * @method static string temperature(string|int|float $temp, bool $fahrenheit = true)
 * @method static string swapLeft(string $str, string $substr, string $replace)
 * @method static string swapRight(string $str, string $substr, string $replace)
 * @method static string limit(string $str, int $length, string $append = '...')
 * @method static string abbr(string $str, int $length, string $append = '...')
 * @method static string utf8(string $str)
 * @method static string sanitizeHtml()
 */
class StrStatic
{
    /**
     * Call any Str method statically.
     *
     * @param string $method
     * @param array  $arguments
     * @return mixed
     */
    public static function __callStatic(string $method, array $arguments)
    {
        $str = Arr::removeFirst($arguments);

        Assert::methodExists($method, static::class);

        $result = Str::make($str)->{$method}(...$arguments);

        if ($result instanceof Str) {
            return (string) $result;
        }

        return $result;
    }
}
