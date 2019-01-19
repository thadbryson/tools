<?php

declare(strict_types = 1);

namespace Tool\Support;

use Tool\Support\Traits\Collection as CollectionTraits;
use function array_walk_recursive;

/**
 * Collection Class
 *
 * Extra functionality to Laravel Collection class.
 *
 * @method bool has(string|int ...$keys)
 */
class Collection extends \Illuminate\Support\Collection
{
    use CollectionTraits\AliasMethodsTrait,
        CollectionTraits\FromTypesTrait,
        CollectionTraits\KeyMethodsTrait,
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
     * Set Collection items to what is passed in.
     *
     * @param array $items
     *
     * @return \Illuminate\Support\Collection
     */
    public function reset(array $items): Collection
    {
        return new static($items);
    }

    /**
     * Remove all items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function clear(): Collection
    {
        return $this->reset([]);
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

    public function hasDot(string ...$dots): bool
    {
        return Arr::has($this->items, $dots);
    }

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
    public function trimAll(string $chars = " \t\n\r\0\x0B"): Collection
    {
        $this->items = Arr::trimAll($this->items, $chars);

        return $this;
    }
}
