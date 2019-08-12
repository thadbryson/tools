<?php

declare(strict_types = 1);

namespace Tool;

use Illuminate\Support\Pluralizer;
use Tool\Traits\Str as StrTraits;
use function hexdec;
use function strlen;
use function utf8_encode;

/**
 * Class Str
 *
 */
class Str extends \Stringy\Stringy
{
    use StrTraits\AbbrTrait,
        StrTraits\BooleanTrait,
        StrTraits\FormatingTrait,
        StrTraits\JsonTrait,
        StrTraits\MethodTrait,
        StrTraits\StaticMakeTrait;

    /**
     * Explode string into an array.
     *
     * @return string[]
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
        if ($this->endsWith($substr) === false) {
            return $this;
        }

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

        return $this->substr($this->str, $index);
    }

    /**
     * Remove $substr from string entirely.
     *
     * @param string ...$substrs
     * @return $this
     */
    public function remove(string ...$substrs)
    {
        foreach ($substrs as $substr) {
            $this->str = $this->replace($substr, '')->get();
        }

        return $this;
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
}
