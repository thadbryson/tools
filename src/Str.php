<?php

declare(strict_types = 1);

namespace Tool\Support;

use Illuminate\Support\Pluralizer;
use Ramsey\Uuid\Uuid;
use Tool\Validation\Assert;
use function json_decode;
use function json_last_error;
use function json_last_error_msg;
use function strlen;
use const JSON_ERROR_NONE;

/**
 * Class Str
 */
class Str extends \Stringy\Stringy
{
    public static function make(string $str, string $encoding = null): self
    {
        return static::create($str, $encoding);
    }

    public static function implode(string $glue, array $parts, string $encoding = null): self
    {
        $str = implode($glue, $parts);

        return static::create($str, $encoding);
    }

    /**
     * Explode string into an array.
     */
    public function explode(string $delimeter): array
    {
        Assert::stringNotEmpty($delimeter, '$delimeter cannot be an empty string.');

        return explode($delimeter, $this->get());
    }

    /**
     * Get a v4 UUID string.
     */
    public static function uuid(): string
    {
        return Uuid::uuid4()->toString();
    }

    public static function random(int $length, string $encoding = null,
        string $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): self
    {
        $random      = '';
        $charsLength = mb_strlen($chars, '8bit') - 1;

        for ($i = 0;$i < $length;$i++) {
            $random .= $chars[random_int(0, $charsLength)];
        }

        return static::make($random, $encoding)->shuffle();
    }

    public function get(): string
    {
        return $this->__toString();
    }

    public function isEmpty(): bool
    {
        return $this->get() === '';
    }

    public function isNotEmpty(): bool
    {
        return $this->get() !== '';
    }

    public function beforeSubstr(string $substr, int $offset = 0): self
    {
        $index = $this->indexOf($substr, $offset);

        return $this->substr(0, $index);
    }

    public function afterSubstr(string $substr, int $offset = 0): self
    {
        $index = $this->indexOf($substr, $offset);
        $index += strlen($substr);

        return $this->substr($index);
    }

    public function jsonDecode()
    {
        return $this->jsonDecodeOptions(true);
    }

    public function jsonDecodeOptions(bool $assoc = false, int $options = 0, int $depth = 512)
    {
        $decoded = json_decode($this->str, $assoc, $depth, $options);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('String is not valid JSON: ' . $this->get());
        }

        return $decoded;
    }

    /**
     * Get the plural form of an English word.
     */
    public function plural(int $count = 2): self
    {
        $this->str = Pluralizer::plural($this->str, $count);

        return $this;
    }

    private function getAsMethod(string $prepend, string $append): self
    {
        return $this->replace('_', ' ')
                    ->prepend($prepend . ' ')
                    ->append(' ' . $append)
                    ->titleize()
                    ->lowerCaseFirst()
                    ->replace(' ', '');
    }

    public function getter(string $append = ''): self
    {
        return $this->getAsMethod('get', $append);
    }

    public function setter(string $append = ''): self
    {
        return $this->getAsMethod('set', $append);
    }

    public function hasser(string $append = ''): self
    {
        return $this->getAsMethod('has', $append);
    }

    public function isser(string $append = ''): self
    {
        return $this->getAsMethod('is', $append);
    }

    public function hasSubstr(string $substr, bool $caseSensitive = true): bool
    {
        Assert::stringNotEmpty($substr, '$substr cannot be an empty string.');

        return $this->contains($substr, $caseSensitive);
    }
}
