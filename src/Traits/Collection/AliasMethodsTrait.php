<?php

declare(strict_types = 1);

namespace Tool\Support\Traits\Collection;

use Illuminate\Support\Collection as BaseCollection;
use Tool\Support\Collection;

/**
 * Class AliasMethodsTrait
 *
 * @mixin Collection
 */
trait AliasMethodsTrait
{
    public function blacklist(array $dots): BaseCollection
    {
        return $this->forget($dots);
    }

    public function whitelist(array $dots): BaseCollection
    {
        return $this->only($dots);
    }

    public function set(string $key, $value): BaseCollection
    {
        return $this->put($key, $value);
    }

    public function remove(array $keys): BaseCollection
    {
        return $this->forget($keys);
    }

    public function removeFirst()
    {
        return $this->shift();
    }

    public function removeLast()
    {
        return $this->pop();
    }
}