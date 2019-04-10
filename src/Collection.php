<?php

declare(strict_types = 1);

namespace Tool;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Tool\Traits\Collection as CollectionTraits;
use Tool\Validation\Assert;
use function array_walk_recursive;
use function call_user_func;
use function is_object;
use function is_string;
use function method_exists;

/**
 * Collection Class
 *
 * Extra functionality to Laravel Collection class.
 *
 * @method bool has(string|int ...$keys)
 */
class Collection extends \Illuminate\Support\Collection
{
    public const MAP_KEY = '@key';

    use CollectionTraits\AliasMethodsTrait,
        CollectionTraits\FromTypesTrait,
        CollectionTraits\KeyMethodsTrait,
        CollectionTraits\RestrictedTrait,
        CollectionTraits\ValidationTrait;

    /**
     * Set default values on each item in the Collection.
     *
     * @param array $defaults
     *
     * @return $this
     */
    public function defaults(array $defaults): Collection
    {
        $this->items = Arr::defaults($this->items, $defaults);

        return $this;
    }

    /**
     * Remove all items.
     *
     * @return Collection
     */
    public function clear(): Collection
    {
        return $this->reset([]);
    }

    /**
     * Set Collection items to what is passed in.
     *
     * @param array $items
     *
     * @return Collection
     */
    public function reset(array $items): Collection
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Get many items at once.
     *
     * @param string ...$dots
     *
     * @return array
     */
    public function getMany(string ...$dots): array
    {
        return Arr::getMany($this->items, ...$dots);
    }

    /**
     * Get many items at once.
     *
     * @param array $dotOrDefaults
     *
     * @return array
     */
    public function getManyOrDefault(array $dotOrDefaults): array
    {
        $found = [];

        foreach ($dotOrDefaults as $dot => $default) {
            $found[$dot] = $this->get($dot, $default);
        }

        return $found;
    }

    /**
     * Does Collection have all these DOT keys?
     *
     * @param string ...$dots
     *
     * @return bool
     */
    public function hasDot(string ...$dots): bool
    {
        return Arr::has($this->items, $dots);
    }

    /**
     * Get value at this DOT key.
     *
     * @param string $dot
     * @param null   $default
     *
     * @return mixed
     */
    public function getDot(string $dot, $default = null)
    {
        return Arr::get($this->items, $dot, $default);
    }

    /**
     * Get many items at once.
     *
     * @param array  $dotAndValues
     * @param string $prepend = ''
     *
     * @return $this
     */
    public function setMany(array $dotAndValues, string $prepend = ''): Collection
    {
        $this->items = Arr::setMany($this->items, $dotAndValues, $prepend);

        return $this;
    }

    /**
     * Change every value (nested too) by returned result of $callback($value, $key).
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function actOnAll(callable $callback): Collection
    {
        array_walk_recursive($this->items, function (&$value, $key) use ($callback) {

            $value = $callback($value, $key);
        });

        return $this;
    }

    /**
     * Change every value (nested too) if $ifCallback($value, $key) returns TRUE
     * by returned result of $callback($value, $key).
     *
     * @param callable $callback
     * @param callable $ifCallback
     *
     * @return $this
     */
    public function actOnAllIf(callable $callback, callable $ifCallback): Collection
    {
        array_walk_recursive($this->items, function (&$value, $key) use ($callback, $ifCallback) {

            if ($ifCallback($value, $key) === true) {

                $value = $callback($value, $key);
            }
        });

        return $this;
    }

    /**
     * Run trim() on every string value.
     *
     * @param string $chars = " \t\n\r\0\x0B"
     *
     * @return $this
     */
    public function trimEverything(string $chars = " \t\n\r\0\x0B"): Collection
    {
        return $this->actOnAllIf(function ($value) use ($chars) {

            return trim($value, $chars);
        }, function ($value): bool {

            return is_string($value) === true;
        });
    }

    /**
     * Run utf8_encode() on every string value.
     *
     * @return $this
     */
    public function utf8Everything(): Collection
    {
        $test = function ($value): bool {
            return is_string($value) === true;
        };

        return $this->actOnAllIf(function ($value) {

            return StrStatic::utf8($value);
        }, $test);
    }

    /**
     * Run a callable on all the items in an array - recursively.
     *
     * @param callable $callable
     * @param mixed    ...$args
     *
     * @return $this
     */
    public function callEverything(callable $callable, ...$args): Collection
    {
        return $this->actOnAll(function ($value) use ($callable, $args) {

            return call_user_func($callable, $value, ...$args);
        });
    }

    public function saveAll(): Collection
    {
        return $this->each(function ($model) {

            if (is_object($model) && method_exists($model, 'save')) {
                $model->save();
            }
        });
    }

    public function deleteAllModels(): Collection
    {
        return $this->each(function (object $model) {

            if (method_exists($model, 'delete')) {
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
