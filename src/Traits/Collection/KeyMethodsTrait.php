<?php

declare(strict_types = 1);

namespace Tool\Traits\Collection;

use function array_walk;
use Tool\Arr;
use Tool\Collection;
use function array_combine;

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
     *
     * @return Collection
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
     * @param string[] $keys
     * @return static
     */
    public function combineEach(array $keys)
    {
        return $this->map(function (array $item) use ($keys) {

            array_walk($keys, function (&$key) {

                return (string) $key;
            });

            return array_combine($keys, $item);
        });
    }
}
