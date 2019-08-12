<?php

declare(strict_types = 1);

namespace Tool;

use Tool\Traits\Collection as CollectionTraits;
use function array_walk_recursive;
use function is_string;

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
        CollectionTraits\CastTrait,
        CollectionTraits\FilterTrait,
        CollectionTraits\FromTypesTrait,
        CollectionTraits\KeyTrait,
        CollectionTraits\KeyIndexTrait,
        CollectionTraits\ModelTrait,
        CollectionTraits\RejectTrait,
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
        return $this->actOnAllIf(function ($value) {

            return StrStatic::utf8($value);
        }, function ($value): bool {

            return is_string($value) === true;
        });
    }

    public function mapColumn($column, callable $map): Collection
    {
        return $this->map(function (array $rowData) use ($column, $map) {

            if (array_key_exists($column, $rowData)) {
                $rowData[$column] = $map($rowData[$column]);
            }

            return $rowData;
        });
    }

    public function mapColumns(?callable ...$maps): Collection
    {
        $mapped = $this;

        foreach ($maps as $column => $map) {

            if ($map !== null) {
                $mapped = $mapped->mapColumn($column, $map);
            }
        }

        return $mapped;
    }

    /**
     * Randomize entire Collection. Return new Collection.
     *
     * @return Collection
     * @throws Validation\Exceptions\ValidationException
     */
    public function randomize(): Collection
    {
        return new Collection($this->random($this->count()));
    }

    /**
     * Get and remove value from Collection where callable passes first.
     *
     * @param callable $callable
     * @return mixed
     */
    public function pullFirst(callable $callable)
    {
        $key = $this->firstKey($callable);

        return $this->pull($key);
    }
}
