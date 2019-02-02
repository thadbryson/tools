<?php

declare(strict_types = 1);

namespace Tool\Traits\Str;

use function is_numeric;
use Tool\Str;
use Tool\Validation\Assert;
use function Tool\Functions\String\is_json;
use function Tool\Functions\String\is_numeric_float;
use function Tool\Functions\String\is_numeric_int;
use function Tool\Functions\String\is_timezone;

/**
 * Trait BooleanTraits
 *
 * @mixin Str
 */
trait BooleanTraits
{
    public function isJson(): bool
    {
        if (is_string($this->get())) {
            json_decode($this->get());

            return json_last_error() === JSON_ERROR_NONE;
        }

        return false;
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
        if (is_int($this->get())) {
            return true;
        }

        return is_string($this->get()) && is_numeric($this->get()) && strpos($this->get(), '.') === false;
    }

    /**
     * Is this a numberic string of a float?
     */
    public function isNumericFloat(): bool
    {
        if (is_float($this->get())) {
            return true;
        }

        return is_string($this->get()) && is_numeric($this->get()) && strpos($this->get(), '.') !== false;
    }
}