<?php

declare(strict_types = 1);

namespace Tool;

use Illuminate\Support\Pluralizer;
use Illuminate\Support\Str as LaravelStr;
use Stringy\Stringy;
use Tool\Traits\Str as StrTraits;
use function strlen;
use function utf8_encode;

/**
 * Class Str
 *
 */
class Str extends Stringy
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
     */
    public function get(): string
    {
        return $this->__toString();
    }

    /**
     * Swap left side text $substr with $replace.
     */
    public function swapLeft(string $substr, string $replace): self
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
     */
    public function swapRight(string $substr, string $replace): self
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
     */
    public function beforeSubstr(string $substr, int $offset = 0): self
    {
        $index = $this->indexOf($substr, $offset);

        return $this->substr(0, (int) $index);
    }

    /**
     * Get text after substring $substr from offset $offset.
     */
    public function afterSubstr(string $substr, int $offset = 0): self
    {
        $index = $this->indexOf($substr, $offset);
        $index += strlen($substr);

        return $this->substr($index);
    }

    /**
     * Remove $substr from string entirely.
     */
    public function remove(string ...$substrs): self
    {
        foreach ($substrs as $substr) {
            $this->str = $this->replace($substr, '')->get();
        }

        return $this;
    }

    /**
     * Get the plural form of an English word.
     */
    public function plural(int $count = 2): self
    {
        $this->str = Pluralizer::plural($this->str, $count);

        return $this;
    }

    /**
     * UTF-8 encode the string.
     */
    public function utf8(): self
    {
        $this->replace("\'", '');

        $this->str = utf8_encode($this->str);

        return $this;
    }

    /**
     * Generate random string
     */
    public function random(int $count = 50): self
    {
        $this->str = LaravelStr::random($count);

        return $this;
    }

    /**
     * Format filesize memory to abbreviated units. Ex: 50 MB, 20KB
     */
    public function memory(int $precision = 2): self
    {
        $suffix = ['', 'KB', 'MB', 'GB', 'TB'];

        $base  = log((int) $this->str) / log(1024);
        $index = floor($base);

        $size = pow(1024, $base - floor($base));

        $this->str = round($size, $precision) . ' ' . $suffix[(string) $index];

        return $this;
    }
}
