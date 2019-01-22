<?php

declare(strict_types = 1);

namespace Tool\Support\Traits\Collection;

use Tool\Support\Arr;
use Tool\Support\Collection;

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
        $this->items = Arr::dot($this->items, $prepend);

        return $this;
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
        $this->items = Arr::undot($this->items, $prepend);

        return $this;
    }
}