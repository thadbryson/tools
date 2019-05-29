<?php

declare(strict_types = 1);

namespace Tool\Traits\Collection;

use Tool\Cast;
use Tool\Collection;

/**
 * Trait CastTrait
 *
 * @mixin Collection
 */
trait CastTrait
{
    public function castInteger(): Collection
    {
        return $this->map(function ($value) {

            return Cast::toInteger($value);
        });
    }

    public function castString(): Collection
    {
        return $this->map(function ($value) {

            return Cast::toString($value);
        });
    }
}
