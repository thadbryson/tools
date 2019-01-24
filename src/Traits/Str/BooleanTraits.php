<?php

declare(strict_types = 1);

namespace Tool\Support\Traits\Str;

use function tool\functions\string\is_numeric_float;
use function tool\functions\string\is_numeric_int;
use function tool\functions\string\is_timezone;
use tool\support;
use Tool\Support\Str;
use Tool\Support\Validation\Assert;

/**
 * Trait BooleanTraits
 *
 * @mixin Str
 */
trait BooleanTraits
{
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
        Assert::stringNotEmpty($substr, '$substr cannot be an empty string.');

        return $this->contains($substr, $caseSensitive);
    }

    /**
     * Is variable given a valid DateTimeZone string?
     */
    public function isTimezone(): bool
    {
        return is_timezone($this->get());
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
        return is_numeric_int($this->get());
    }

    /**
     * Is this a numberic string of a float?
     */
    public function isNumericFloat(): bool
    {
        return is_numeric_float($this->get());
    }
}