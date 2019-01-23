<?php

declare(strict_types = 1);

namespace Tool\Support\Traits\Collection;

use Tool\Support\Collection;
use Tool\Validation\Assert;
use Tool\Validation\Result;
use Tool\Validation\Validator;

/**
 * Class ValidationTrait
 *
 * @mixin Collection
 */
trait ValidationTrait
{
    /**
     * Validate the data and return a Result object with any error messages.
     *
     * @param array $rules
     * @param array $messages         = []
     * @param array $customAttributes = []
     *
     * @return Result
     */
    public function validate(array $rules, array $messages = [], array $customAttributes = []): Result
    {
        return Validator::validate($this->items, $rules, $messages, $customAttributes);
    }

    /**
     * @param array $values - DOT key to compare => value expected
     *
     * @return Collection
     * @throws \InvalidArgumentException
     */
    public function assertEquals(array $values): Collection
    {
        // Get current values.
        foreach ($values as $dot => $expected) {

            Assert::true($this->hasDot($dot), "Key does not exist: {$dot}");

            Assert::eq($this->getDot($dot), $expected, "Value '%s' is not what is expected for key: {$dot}.");
        }

        return $this;
    }
}
