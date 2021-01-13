<?php

declare(strict_types = 1);

namespace Tool\Traits\Str;

use Tool\Str;
use function in_array;
use function is_float;
use function is_int;
use function is_numeric;

/**
 * Trait BooleanTraits
 *
 * @mixin Str
 */
trait BooleanTrait
{
    public function isJson(): bool
    {
        json_decode($this->get());

        return json_last_error() === JSON_ERROR_NONE;
    }

    public function isEmpty(): bool
    {
        return $this->get() === '';
    }

    public function isNotEmpty(): bool
    {
        return $this->get() !== '';
    }

    public function hasSubstr(string $substr, bool $caseSensitive = true): bool
    {
        return $this->contains($substr, $caseSensitive);
    }

    /**
     * Is variable given a valid DateTimeZone string?
     */
    public function isTimezone(): bool
    {
        try {
            new \DateTimeZone($this->get());

            return true;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Is this a numeric string of an integer?
     */
    public function isNumeric(): bool
    {
        return is_numeric($this->get());
    }

    /**
     * Is this a numeric string of an integer?
     */
    public function isNumericInt(): bool
    {
        $str = $this->get();

        return is_int($str) || (is_string($str) && is_numeric($str) && strpos($str, '.') === false);
    }

    /**
     * Is this a numberic string of a float?
     */
    public function isNumericFloat(): bool
    {
        $str = $this->get();

        return is_float($str) || (is_string($str) && is_numeric($str) && strpos($str, '.') !== false);
    }

    public function inArray(array $options, bool $strict = false): bool
    {
        return in_array($this->str, $options, $strict);
    }
}
