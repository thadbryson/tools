<?php

declare(strict_types = 1);

namespace Tool\Traits\Collection;

use Tool\Collection;
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
     * Validate the data and return a Mapped object with any error messages.
     *
     * @param array $rules
     * @param array $messages = []
     * @param array $customAttributes = []
     *
     * @return Result
     */
    public function validate(array $rules, array $messages = [], array $customAttributes = []): Result
    {
        return Validator::validate($this->items, $rules, $messages, $customAttributes);
    }

    /**
     * Assert each DOT key => value are a strict equals.
     *
     * @param string $dot
     * @param mixed  $value
     *
     * @return Collection
     * @throws \InvalidArgumentException
     */
    public function assertEquals(string $dot, $value): Collection
    {
        Assert::true($this->hasDot($dot), "Key does not exist: {$dot}");
        Assert::same($this->getDot($dot), $value, "Value '%s' is not what is expected for key: {$dot}.");

        return $this;
    }

    /**
     * Assert each DOT key => value are a strict equals.
     *
     * @param array $values - DOT key to compare => value expected
     *
     * @return Collection
     * @throws \InvalidArgumentException
     */
    public function assertEqualsAll(array $values): Collection
    {
        foreach ($values as $dot => $expected) {
            $this->assertEquals($dot, $expected);
        }

        return $this;
    }
}
