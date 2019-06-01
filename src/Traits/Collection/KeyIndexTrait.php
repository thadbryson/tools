<?php

declare(strict_types = 1);

namespace Tool\Traits\Collection;

use function array_combine;
use Tool\Collection;

/**
 * Trait KeyIndexTrait
 *
 * @mixin Collection
 */
trait KeyIndexTrait
{
    /**
     * Insert $values at position index.
     *
     * @param int   $index
     * @param mixed ...$values
     * @return Collection
     * @throws \Tool\Validation\Exceptions\ValidationException
     */
    public function insertAt(int $index, ...$values): Collection
    {
        $items = $this->items;
        array_splice($items, $index, 0, $values);

        return new static($items);
    }

    /**
     * Set (and replace) $values at position index.
     *
     * @param int   $index
     * @param mixed ...$values
     * @return Collection
     * @throws \Tool\Validation\Exceptions\ValidationException
     */
    public function setAt(int $index, ...$values): Collection
    {
        $items = $this->items;
        array_splice($items, $index, count($values), $values);

        return new static($items);
    }

    /**
     * Get value at index.
     *
     * @param int   $index
     * @param mixed $default = null
     * @return mixed
     */
    public function getAt(int $index, $default = null)
    {
        return $this->values()->get($index, $default);
    }

    /**
     * Get and remove value at index.
     *
     * @param int   $index
     * @param mixed $default = null
     * @return mixed
     */
    public function pullAt(int $index, $default = null)
    {
        $keys   = $this->keys()->forget($index);    // remove key.
        $values = $this->values();

        // Find value to return (and remove).
        $found = $values->pull($index, $default);

        // Map keys to values (current object) without found key/value.
        $this->items = array_combine($keys->all(), $values->all());

        return $found;
    }
}
