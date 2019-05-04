<?php

declare(strict_types = 1);

namespace Tool;

use Illuminate\Support\Pluralizer;
use Tool\Traits\Str as StrTraits;
use Tool\Validation\Assert;
use function hexdec;
use function json_decode;
use function json_last_error;
use function strlen;
use function utf8_encode;
use const JSON_ERROR_NONE;

/**
 * Class Str
 *
 */
class Str extends \Stringy\Stringy
{
    use StrTraits\BooleanTraits,
        StrTraits\StaticMakeTrait;

    /**
     * Explode string into an array.
     */
    public function explode(string $delimiter): array
    {
        return explode($delimiter, $this->get());
    }

    /**
     * Get raw string value.
     *
     * @return string
     */
    public function get(): string
    {
        return $this->__toString();
    }

    /**
     * Swap left side text $substr with $replace.
     *
     * @param string $substr
     * @param string $replace
     * @return $this|Str
     */
    public function swapLeft(string $substr, string $replace)
    {
        if ($this->startsWith($substr) === false) {
            return $this;
        }

        return $this
            ->removeLeft($substr)
            ->prepend($replace);
    }

    /**
     * Swap right side text $substr with $replace.
     *
     * @param string $substr
     * @param string $replace
     * @return $this
     */
    public function swapRight(string $substr, string $replace)
    {
        return $this
            ->removeRight($substr)
            ->append($replace);
    }

    /**
     * Get text before substring $substr from offset $offset.
     *
     * @param string $substr
     * @param int    $offset
     * @return $this
     */
    public function beforeSubstr(string $substr, int $offset = 0)
    {
        $index = $this->indexOf($substr, $offset);

        return $this->substr(0, $index);
    }

    /**
     * Get text after substring $substr from offset $offset.
     *
     * @param string $substr
     * @param int    $offset
     * @return Str
     */
    public function afterSubstr(string $substr, int $offset = 0)
    {
        $index = $this->indexOf($substr, $offset);
        $index += strlen($substr);

        return $this->substr($index);
    }

    /**
     * Perform standard json_decode of string.
     *
     * @return mixed
     */
    public function jsonDecode()
    {
        return $this->jsonDecodeOptions(true);
    }

    /**
     * Persom json_decode with options.
     *
     * @param bool $assoc
     * @param int  $options
     * @param int  $depth
     * @return mixed
     */
    public function jsonDecodeOptions(bool $assoc = false, int $options = 0, int $depth = 512)
    {
        $decoded = json_decode($this->str, $assoc, $depth, $options);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('String is not valid JSON: ' . $this->get());
        }

        return $decoded;
    }

    /**
     * Remove $substr from string entirely.
     *
     * @param string $substr
     * @return $this
     */
    public function remove(string $substr)
    {
        return $this->replace($substr, '');
    }

    /**
     * Get the plural form of an English word.
     *
     * @return $this
     */
    public function plural(int $count = 2)
    {
        $this->str = Pluralizer::plural($this->str, $count);

        return $this;
    }

    /**
     * If string is longer than $length then shorten and add $append string at end.
     *
     * @param int    $length
     * @param string $append = '...'
     * @return $this
     */
    public function limit(int $length, string $append = '...')
    {
        $this->str = \Illuminate\Support\Str::limit($this->str, $length, $append);

        return $this;
    }

    /**
     * Return shortened text in <abbr> tag.
     *
     * @param int    $length
     * @param string $append = '...
     * @return $this
     */
    public function abbr(int $length, string $append = '...')
    {
        if ($length < strlen($this->str)) {
            $str = $this->limit($length, $append);

            $this->str = sprintf('<abbr title="%s">%s</abbr>', $this->str, $str);
        }

        return $this;
    }

    /**
     * Use <abbr> text with different text placeholder.
     *
     * @param string $text
     * @param int    $length
     * @param string $append
     * @return $this
     */
    public function abbrText(string $text, int $length, string $append = '...')
    {
        $str = $this->limit($length, $append);

        $this->str = sprintf('<abbr title="%s">%s</abbr>', $text, $str);

        return $this;
    }

    /**
     * Get as a "getter" method name.
     *
     * @param string $append
     * @return $this
     */
    public function getter(string $append = '')
    {
        return $this->codeMethod('get', $append);
    }

    /**
     * Get as a "setter" method name.
     *
     * @param string $append
     * @return $this
     */
    public function setter(string $append = '')
    {
        return $this->codeMethod('set', $append);
    }

    /**
     * Get as a "hasser" method name.
     *
     * @param string $append
     * @return $this
     */
    public function hasser(string $append = '')
    {
        return $this->codeMethod('has', $append);
    }

    /**
     * Get as an "isser" method name.
     *
     * @param string $append
     * @return $this
     */
    public function isser(string $append = '')
    {
        return $this->codeMethod('is', $append);
    }

    /**
     * Get monetery string format.
     *
     * @return $this
     */
    public function money(string $locale = 'en_US', string $format = '%n')
    {
        Assert::numeric($this->get(), '$var must be a numeric string, integer, or float.');
        setlocale(LC_MONETARY, $locale . '.' . $this->getEncoding());

        $this->str = money_format($format, (float) $this->get());

        return $this;
    }

    /**
     * Get international monetery format.
     *
     * @return $this
     */
    public function moneyInternational(string $locale = 'en_US')
    {
        return $this->money($locale, '%i');
    }

    /**
     * Format to temperature. Ex: '75 &deg; F'
     *
     * @param bool $fahrenheit
     * @return $this
     */
    public function temperature(bool $html = true, bool $fahrenheit = true)
    {
        $sign = $html ? '&deg; ' : '° ';
        $sign .= $fahrenheit ? 'F' : 'C';

        $this->str .= $sign;

        return $this;
    }

    /**
     * UTF-8 encode the string.
     *
     * @return $this
     */
    public function utf8()
    {
        $this->replace("\'", '');

        $this->str = utf8_encode($this->str);

        return $this;
    }

    /**
     * Get text as a method.
     *
     * @param string $prepend
     * @param string $append
     * @return $this
     */
    public function codeMethod(string $prepend, string $append)
    {
        return $this->replace('_', ' ')
            ->prepend($prepend . ' ')
            ->append(' ' . $append)
            ->titleize()
            ->lowerCaseFirst()
            ->replace(' ', '');
    }

    /**
     * Convert hex color codes to RGB.
     *
     * @return int[]|null
     */
    public function colorHexToRgb(): ?array
    {
        $str = $this->trim()->trimLeft('#');

        if ($str->length() !== 6 || $str->isHexadecimal() === false) {
            return null;
        }

        $red   = $str->substr(0, 2)->get();
        $green = $str->substr(2, 2)->get();
        $blue  = $str->substr(4, 2)->get();

        return [
            'red'   => hexdec($red),
            'green' => hexdec($green),
            'blue'  => hexdec($blue)
        ];
    }
}
