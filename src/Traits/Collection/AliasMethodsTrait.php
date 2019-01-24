<?php

declare(strict_types = 1);

namespace Tool\Traits\Collection;

use Tool\Arr;
use Tool\Collection;

/**
 * Class AliasMethodsTrait
 *
 * @mixin Collection
 */
trait AliasMethodsTrait
{
    /**
     * @alias push($value)
     */
    public function append($value): Collection
    {
        return $this->push($value);
    }

    public function set(string $dot, $value): Collection
    {
        Arr::set($this->items, $dot, $value);

        return $this;
    }

    /**
     * Remove given DOT keys.
     */
    public function remove(string ...$dots): Collection
    {
        $this->items = Arr::remove($this->items, ...$dots);

        return $this;
    }

    /**
     * Remove first value.
     *
     * @return mixed
     */
    public function removeFirst()
    {
        return $this->shift();
    }

    /**
     * Remove last value.
     *
     * @return mixed
     */
    public function removeLast()
    {
        return $this->pop();
    }
}
