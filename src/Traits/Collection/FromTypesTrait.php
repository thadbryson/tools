<?php

declare(strict_types = 1);

namespace Tool\Traits\Collection;

use Illuminate\Http\Request;
use function json_decode;
use Tool\Collection;
use function array_replace_recursive;

/**
 * Trait FromTypesTrait
 *
 * @mixin Collection
 */
trait FromTypesTrait
{
    public static function fromJson(string $json, int $depth = 512, int $options = 0): Collection
    {
        return static::make((array) json_decode($json, true, $depth, $options));
    }

    /**
     * Build Collection from exploded string.
     *
     * @param string $delimiter
     * @param string $string
     * @param int    $limit = null
     *
     * @return Collection
     */
    public static function fromExplodeString(string $delimiter, string $string, int $limit = null): Collection
    {
        $args = [$delimiter, $string];

        if ($limit !== null) {
            $args[] = $limit;
        }

        $items = explode(...$args);

        return static::make($items);
    }

    /**
     * Load HTTP Request DOT keys.
     *
     * @param Request|null $request = null - If NULL will capture the current HTTP request.
     * @param string       ...$dots
     *
     * @return Collection
     */
    public static function fromRequest(Request $request = null): Collection
    {
        return static::make()->loadRequest($request);
    }

    /**
     * Load HTTP Request DOT keys.
     *
     * @param Request|null $request = null - If NULL will capture the current HTTP request.
     * @param string       ...$dots
     *
     * @return Collection
     */
    public function loadRequest(Request $request = null): Collection
    {
        $request = $request ?? Request::createFromGlobals();

        $this->items = array_replace_recursive($this->items, $request->input());

        return $this;
    }
}
