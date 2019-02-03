<?php

declare(strict_types = 1);

namespace Tool\Traits\Collection;

use InvalidArgumentException;
use Tool\Arr;
use Tool\Collection;
use function array_combine;
use function array_walk;

/**
 * Class KeyMethodsTrait
 *
 * @mixin Collection
 */
trait KeyMethodsTrait
{
    /**
     * Make array DOT notation.
     *
     * @param string $prepend
     * @return Collection
     * @throws \Tool\Validation\Exceptions\ValidationException
     */
    public function dot(string $prepend = ''): Collection
    {
        $items = Arr::dot($this->items, $prepend);

        /** @noinspection PhpMethodParametersCountMismatchInspection */
        return new static($items);
    }

    /**
     * Make DOT Collection keys to regular multi-dimensional array.
     *
     * @param string $prepend
     *
     * @return Collection
     * @throws \Tool\Validation\Exceptions\ValidationException
     */
    public function undot(string $prepend = ''): Collection
    {
        $items = Arr::undot($this->items, $prepend);

        /** @noinspection PhpMethodParametersCountMismatchInspection */
        return new static($items);
    }

    /**
     * Set keys "combine" for each array in the Collection.
     *
     * @param string ...$keys
     * @return Collection
     */
    public function combineEach(string ...$keys): Collection
    {
        return $this->map(function (array $item) use ($keys) {

            array_walk($keys, function (&$key) {
                return (string) $key;
            });

            return array_combine($keys, $item);
        });
    }

    /**
     * Use row as a header.
     *
     * @param int  $offset = 0
     * @return Collection
     */
    public function mapHeader(int $offset = 0): Collection
    {
        $header = $this->pullAt($offset, []);

        // Set keys on header.
        return $this->combineEach(...$header);
    }

    /**
     * Insert $values at position index.
     *
     * @param int   $index
     * @param mixed ...$values
     * @return Collection
     */
    public function insertAt(int $index, ...$values): Collection
    {
        return $this->splice($index, null, $values);
    }

    /**
     * Set (and replace) $values at position index.
     *
     * @param int   $index
     * @param mixed ...$values
     * @return Collection
     */
    public function setAt(int $index, ...$values): Collection
    {
        return $this->splice($index, count($values), $values);
    }

    /**
     * Get value at index.
     *
     * @param int   $index
     * @param mixed $default = null
     * @return array|null
     */
    public function getAt(int $index, $default = null)
    {
        return array_splice($this->items, $index, null) ?? $default;
    }

    /**
     * Get and remove value at index.
     *
     * @param int   $index
     * @param mixed $default = null
     * @return array|null
     */
    public function pullAt(int $index, $default = null)
    {
        return array_splice($this->items, $index, 1) ?? $default;
    }
}
