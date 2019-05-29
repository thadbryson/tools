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
        return $this->each(function ($model) {

            if (is_object($model) && method_exists($model, 'save')) {
                $model->save();
            }
        });
    }

    public function deleteAll(): Collection
    {
        return $this->each(function (object $model) {

            if (is_object($model) && method_exists($model, 'delete')) {
                $model->delete();
            }
        });
    }

    public function deleteNot(Builder $query = null, string $searchKey = 'id'): Collection
    {
        if ($query === null) {
            $first = Assert::isSubclassOf($this->first(), Model::class, 'First element is not a Model. Please specify a Builder object.');

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
