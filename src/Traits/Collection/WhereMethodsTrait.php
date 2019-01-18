<?php

declare(strict_types = 1);

namespace Tool\Support\Traits\Collection;

use Illuminate\Support\Collection as BaseCollection;
use Tool\Support\Collection;

/**
 * Trait WhereMethodsTrait
 *
 * @mixin Collection
 */
trait WhereMethodsTrait
{
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
        return function ($item) use ($key, $regex, $found) {
            $retrieved = data_get($item, $key);

            return preg_match($regex, $retrieved) === $found;
        };
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
                        /** @noinspection TypeUnsafeComparisonInspection */
                        return $retrieved == $value;
                    case '!=':
                    case '<>':
                        /** @noinspection TypeUnsafeComparisonInspection */
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
                        return false;
                }
            } /** @noinspection PhpVariableNamingConventionInspection */
            catch (\Exception $_) {
                return false;
            }
        };
    }
}