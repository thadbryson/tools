<?php

declare(strict_types = 1);

namespace Tool\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Collection as BaseCollection;
use function explode;

/**
 * Collection Class
 *
 * Extends \Illuminate\Support\Collection class.
 */
class Collection extends BaseCollection
{
    /**
     * @param Request|null $request  = null
     * @param array        $defaults = []
     *
     * @return Collection
     */
    public static function fromRequest(Request $request = null, array $defaults = []): self
    {
        $keys = array_keys($defaults);

        return static::make($defaults)->loadRequest($request, ...$keys);
    }

    public function loadRequest(Request $request = null, string ...$keys): self
    {
        $request = $request ?? Request::createFromGlobals();

        if ($keys === []) {
            $all = $request->all();
        }
        else {
            $all = $request->all($keys);
        }

        return $this->merge($all);
    }

    /**
     * @param string $delimiter
     * @param string $string
     *
     * @return $this
     */
    public static function fromString(string $delimiter, string $string): self
    {
        $items = explode($delimiter, $string);

        return new static($items);
    }

    public function keyOrder(array $dots)
    {
        return new static(Arr::keyOrder($this->items, $dots));
    }

    public function isNotAssocAll(): bool
    {
        foreach ($this->keys()->toArray() as $key) {

            if (is_numeric($key) === false) {
                return false;
            }
        }

        return true;
    }

    public function isAssocAll(): bool
    {
        foreach ($this->keys()->toArray() as $key) {

            if (is_numeric($key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Set Collection items to what is passed in.
     *
     * @param mixed $items
     *
     * @return \Illuminate\Support\Collection
     */
    public function reset($items): BaseCollection
    {
        return new static($items);
    }

    /**
     * Empty all items.
     *
     * @return \Illuminate\Support\Collection
     */
    public function clear(): BaseCollection
    {
        return $this->reset([]);
    }

    /**
     * @param string $key
     * @param        $value
     *
     * @return $this
     */
    public function set(string $key, $value): self
    {
        $this->put($key, $value);

        return $this;
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
            Arr::set($result, $dot, $default);
        }

        return $result;
    }

    /**
     * Cast items with rules provided.
     *
     * @param array $casts
     *
     * @return $this
     */
    public function cast(array $casts): self
    {
        $this->items = Cast::all($casts, $this->items);

        return $this;
    }

    /**
     * Remove several keys at once.
     *
     * @param array $keys
     *
     * @return $this
     */
    public function remove(array $keys): self
    {
        $this->reject(function ($item, $key) use ($keys) {
            return in_array($key, $keys, $strict = false);
        });

        return $this;
    }

    /**
     * Filter items matching Regular expression given.
     *
     * @param string $key
     * @param string $regex
     *
     * @return \Illuminate\Support\Collection
     */
    public function whereRegex(string $key, string $regex): BaseCollection
    {
        return $this->filter(static::operandRegexWhere($key, $regex, 1));
    }

    /**
     * Filter items matching Regular expression given.
     *
     * @param string $key
     * @param string $regex
     *
     * @return \Illuminate\Support\Collection
     */
    public function whereNotRegex(string $key, string $regex): BaseCollection
    {
        return $this->filter(static::operandRegexWhere($key, $regex, 0));
    }

    /**
     * Filter items matching Regular expression given.
     *
     * @param string $key
     * @param string $like
     *
     * @return \Illuminate\Support\Collection
     */
    public function whereLike(string $key, string $like): BaseCollection
    {
        $regex = '/' . str_replace('%', '.+', $like) . '/m';

        return $this->filter(static::operandRegexWhere($key, $regex, 1));
    }

    /**
     * Filter items matching Regular expression given.
     *
     * @param string $key
     * @param string $like
     *
     * @return \Illuminate\Support\Collection
     */
    public function whereNotLike(string $key, string $like): BaseCollection
    {
        $regex = '/' . str_replace('%', '.+', $like) . '/m';

        return $this->filter(static::operandRegexWhere($key, $regex, 0));
    }

    /**
     * Get callable for comparing Regular expressions in where methods.
     *
     * @param string $key
     * @param string $regex
     * @param int    $found
     *
     * @return \Closure
     */
    protected static function operandRegexWhere(string $key, string $regex, int $found): \Closure
    {
        function ($item) use ($key, $regex, $found)
        {
            $retrieved = data_get($item, $key);

            return preg_match($regex, $retrieved) === $found;
        }
    }

    /**
     * Get an operator checker callback. Using strict comparison for '='.
     *
     * @param  string      $key
     * @param  string|null $operator = null
     * @param  mixed       $value
     *
     * @return \Closure
     */
    protected function operatorForWhere($key, $operator = null, $value = null): \Closure
    {
        return function ($item) use ($key, $operator, $value) {
            $retrieved = data_get($item, $key);

            try {
                switch (trim($operator)) {
                    case '=':
                    case '===':
                        return $retrieved === $value;
                    case '!==':
                        return $retrieved !== $value;
                    case '==':
                        return $retrieved == $value;
                    case '!=':
                    case '<>':
                        return $retrieved != $value;
                    case '<':
                        return $retrieved < $value;
                    case '>':
                        return $retrieved > $value;
                    case '<=':
                        return $retrieved <= $value;
                    case '>=':
                        return $retrieved >= $value;
                    default:
                }
            } catch (\Exception $_) {
                return false;
            }
        };
    }
}
