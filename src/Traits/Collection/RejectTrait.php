<?php

declare(strict_types = 1);

namespace Tool\Traits\Collection;

use Tool\Collection;

/**
 * Trait RejectTrait
 *
 * @mixin Collection
 */
trait RejectTrait
{
    public function rejectValue($value): Collection
    {
        return $this->reject(function ($item) use ($value) {

            return $item === $value;
        });
    }

    public function rejectNull(): Collection
    {
        return $this->rejectValue(null);
    }

    public function rejectEmpty(): Collection
    {
        return $this->reject(function ($value) {

            return empty($value);
        });
    }
}