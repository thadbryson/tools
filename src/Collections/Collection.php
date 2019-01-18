<?php

declare(strict_types = 1);

namespace Tool\Support\Collections;

use Illuminate\Support\Collection as BaseCollection;
use Tool\Support\Arr;
use Tool\Support\Traits\Collection as CollectionTraits;
use function array_walk_recursive;

/**
 * Collection Class
 *
 * Extra functionality to Laravel Collection class.
 *
 * @method bool has(string[]|int[] ...$keys)
 */
class Collection extends BaseCollection
{
    use CollectionTraits\AliasMethodsTrait,
        CollectionTraits\FromTypesTrait,
        CollectionTraits\KeyMethodsTrait,
        CollectionTraits\TableTrait,
        CollectionTraits\WhereMethodsTrait,
        CollectionTraits\ValidationTrait;

    /**
     * Set default values on each item in the Collection.
     *
     * @param array $defaults
     *
     * @return BaseCollection
     */
    public function defaults(array $defaults): BaseCollection
    {
        return $this->map(function (array $row) use ($defaults) {

            return Arr::defaults($row, $defaults);
        });
    }

    /**
     * Set Collection items to what is passed in.
     *
     * @param array $items
     *
     * @return \Illuminate\Support\Collection
     */
    public function reset(array $items): BaseCollection
    {
        return new static($items);
    }

    /**
     * Remove all items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function clear(): BaseCollection
    {
        return $this->reset([]);
    }

    /**
     * Get many items at once.
     *
     * @param array $dotWithDefaults
     *
     * @return array
     */
    public function getMany(array $dotWithDefaults): array
    {
        $result = [];

        foreach ($dotWithDefaults as $dot => $default) {
            $result[$dot] = $this->get($dot, $default);
        }

        return $result;
    }

    /**
     * Get many items at once.
     *
     * @param array $dotAndValues
     *
     * @return BaseCollection
     */
    public function setMany(array $dotAndValues): BaseCollection
    {
        foreach ($dotAndValues as $dot => $value) {
            $this->set($dot, $value);
        }

        return $this;
    }

    /**
     * Cast items with rules provided.
     *
     * @param string $type
     * @param array  $dots
     *
     * @return $this
     */
    public function cast(string $type, array $dots): BaseCollection
    {
        foreach ($dots as $dot) {

            $value = $this->get($dot);

            if ($value === null) {
                continue;
            }

            $value = Cast::cast($type, $value);

            $this->set($dot, $value);
        }

        return $this;
    }

    /**
     * Change every value (nested too) by returned result of $callback($value, $key).
     *
     * @param callable $callback
     *
     * @return BaseCollection
     */
    public function actOnAll(callable $callback): BaseCollection
    {
        array_walk_recursive($this->items, $callback);

        return $this;
    }

    /**
     * Change every value (nested too) if $ifCallback($value, $key) returns TRUE
     * by returned result of $callback($value, $key).
     *
     * @param callable $callback
     * @param callable $ifCallback
     *
     * @return BaseCollection
     */
    public function actOnAllIf(callable $callback, callable $ifCallback): BaseCollection
    {
        array_walk_recursive($this->items, function ($value, $key) use ($callback, $ifCallback) {

            if ($ifCallback($value, $key) === true) {

                return $callback($value, $key);
            }
        });

        return $this;
    }

    /**
     * Run trim() on every string value.
     *
     * @param string $chars = " \t\n\r\0\x0B"
     *
     * @return BaseCollection
     */
    public function trimAll(string $chars = " \t\n\r\0\x0B"): BaseCollection
    {
        Arr::trimAll($this->items, $chars);

        return $this;
    }
}
