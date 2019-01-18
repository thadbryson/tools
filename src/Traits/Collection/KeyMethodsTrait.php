<?php

declare(strict_types = 1);

namespace Tool\Support\Traits\Collection;

use Illuminate\Support\Collection as BaseCollection;
use Tool\Support\Arr;
use Tool\Support\Collection;

/**
 * Class KeyMethodsTrait
 *
 * @mixin Collection
 */
trait KeyMethodsTrait
{
    public function dot(string $prepend = ''): BaseCollection
    {
        $this->items = Arr::dot($this->items, $prepend);

        return $this;
    }

    public function undot(string $prepend = ''): BaseCollection
    {
        $this->items = Arr::undot($this->items, $prepend);

        return $this;
    }

    public function orderKeys(array $dots): BaseCollection
    {
        $this->items = Arr::orderKeys($this->items, ...$dots);

        return $this;
    }

    /**
     * Rename keys from (current DOT key => new DOT key)
     *
     * @param array $mappings
     *
     * @return BaseCollection
     */
    public function renameKeys(array $mappings): BaseCollection
    {
        $this->items = Arr::map($this->items, $mappings);

        return $this;
    }

    public function getRowNumber(int $rowNumber)
    {
        return $this->slice($rowNumber, 1);
    }

    public function isAssocAll(): bool
    {
        foreach ($this->keys()->toArray() as $key) {

            if (is_numeric($key)) {
                return false;
            }
        }

        return true;
    }

    public function isNotAssocAll(): bool
    {
        return $this->isAssocAll() === false;
    }
}
