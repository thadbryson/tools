<?php

declare(strict_types = 1);

namespace Tool\Traits\Collection;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Tool\Collection;
use Tool\Validation\Assert;

/**
 * Trait ModelTrait
 *
 * @mixin Collection
 */
trait ModelTrait
{
    public function saveAll(): Collection
    {
        return $this->callMethod('save');
    }

    public function deleteAll(): Collection
    {
        return $this->callMethod('delete');
    }

    public function callMethod(string $methodName, ...$args): Collection
    {
        return $this->map(function ($model) use ($methodName, $args) {

            if (is_object($model) && method_exists($model, $methodName)) {
                $model->{$methodName}(...$args);
            }

            return $model;
        });
    }

    public function deleteNot(Builder $query = null, string $searchKey = 'id'): Collection
    {
        if ($this->isEmpty()) {
            return $this;
        }

        if ($query === null) {
            $first = Assert::isSubclassOf($this->first(), Model::class,
                sprintf('First element in collection is not a %s class object. ' .
                    'Must be unless you specify a %s object in the first argument.',
                    Model::class, Builder::class));

            /** @var Model $first */
            $query = $first->newQuery();
        }

        // Run the delete.
        $query
            ->whereNotIn($searchKey, $this->pluck($searchKey)->all())
            ->delete();

        return $this;
    }
}
