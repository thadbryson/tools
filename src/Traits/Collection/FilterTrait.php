<?php

declare(strict_types = 1);

namespace Tool\Traits\Collection;

use Tool\Collection;

/**
 * Trait RejectTrait
 *
 * @mixin Collection
 */
trait FilterTrait
{
    public function filterValue($value): Collection
    {
        return $this->filter(function ($item) use ($value) {

            return $item === $value;
        });
    }

    public function filterNull(): Collection
    {
        return $this->filterValue(null);
    }
}