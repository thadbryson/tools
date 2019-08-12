<?php

declare(strict_types = 1);

namespace Tool\Traits\Collection;

use function array_keys;
use Illuminate\Database\Eloquent\Model;
use function is_array;
use function is_object;
use function method_exists;
use Tool\Arr;
use Tool\Collection;
use function array_combine;
use function array_walk;

/**
 * Class KeyMethodsTrait
 *
 * @mixin Collection
 */
trait KeyTrait
{
    /**
     * Make array DOT notation.
     *
     * @param string $prepend
     * @return Collection
     * @throws \Tool\Validation\Exceptions\ValidationException
     */
    public function dot(string $prepend = ''): Collection
    {
        $items = Arr::dot($this->items, $prepend);

        /** @noinspection PhpMethodParametersCountMismatchInspection */
        return new static($items);
    }

    /**
     * Make DOT Collection keys to regular multi-dimensional array.
     *
     * @param string $prepend
     *
     * @return Collection
     * @throws \Tool\Validation\Exceptions\ValidationException
     */
    public function undot(string $prepend = ''): Collection
    {
        $items = Arr::undot($this->items, $prepend);

        /** @noinspection PhpMethodParametersCountMismatchInspection */
        return new static($items);
    }

    public function onlyEach(string ...$dots): Collection
    {
        return $this->map(function ($item) use ($dots) {

            if (is_object($item) && method_exists($item, 'only')) {
                return $item->only($dots);
            }

            return Arr::only($item, $dots);
        });
    }

    public function firstKey(callable $callback = null, $default = null)
    {
        if ($this->items !== []) {

            if ($callback === null) {
                return Arr::first(array_keys($this->items));
            }

            foreach ($this->items as $key => $value) {

                if (call_user_func($callback, $value, $key)) {
                    return $key;
                }
            }
        }

        return value($default);
    }
}
