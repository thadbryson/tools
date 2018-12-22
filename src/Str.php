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

    /**
     * Explode string into an array.
     */
    public function explode(string $delimeter, int $limit = null): array
    {
        return explode($this->toString(), $delimeter, $limit);
    }

    public static function implode(string $glue, array $parts, string $encoding = null): self
    {
        $str = implode($glue, $parts);

        return static::create($str, $encoding);
    }

    /**
     * Get a v4 UUID string.
     */
    public static function uuid(): string
    {
        return Uuid::uuid4()->toString();
    }

    public function toString(): string
    {
        return $this->__toString();
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

    public function removeLeftAny(string ...$substrings): self
    {
        foreach ($substrings as $substr) {

            if ($this->startsWith($substr)) {
                return $this->removeLeft($substr);
            }
        }

        return $this;
    }

    public function removeRightAny(string ...$substrings): self
    {
        foreach ($substrings as $substr) {

            if ($this->startsWith($substr)) {
                return $this->removeRight($substr);
            }
        }

        return $this;
    }

    public function jsonDecode(): array
    {
        return $this->jsonDecodeOptions(true);
    }

    public function jsonDecodeOptions(bool $assoc = false, int $options = 0)
    {
        Assert::true($this->isJson(), 'String is not valid JSON.');

        $decoded = json_decode($this->str, $assoc, $options);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException(json_last_error_msg(), 400);
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
                    ->titleize()
                    ->prepend($prepend)
                    ->append($append);
    }

    public function getter(string $append = 'Attribute'): self
    {
        return $this->getAsMethod('get', $append);
    }

    public function setter(string $append = 'Attribute'): self
    {
        return $this->getAsMethod('set', $append);
    }
}
