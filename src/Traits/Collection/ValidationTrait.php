<?php

declare(strict_types = 1);

namespace Tool\Support\Traits\Collection;

use Tool\Support\Collection;
use Tool\Validation\Assert;
use Tool\Validation\Result;
use Tool\Validation\Validator;
use function array_fill_keys;
use function array_keys;

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
        // Get search keys, and search keys => NULL (default)
        $keys = array_keys($values);

        Assert::true($this->has(...$keys), 'All values were not found.');

        // Get current values.
        $found = array_fill_keys($keys, null);
        $found = $this->getMany($found);

        Assert::equals($values, $found, 'Values found did not match expected.');

        return $this;
    }
}
