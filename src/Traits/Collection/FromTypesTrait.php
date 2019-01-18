<?php

declare(strict_types = 1);

namespace Tool\Support\Traits\Collection;

use Illuminate\Support\Collection;
use Tool\Support\Request;

/**
 * Trait FromTypesTrait
 *
 * @mixin \Tool\Support\Collection
 */
trait FromTypesTrait
{
    /**
     * @param Request|null $request  = null
     * @param array        $defaults = []
     *
     * @return Collection
     */
    public static function fromRequest(Request $request = null, array $defaults = []): Collection
    {
        $keys = array_keys($defaults);

        return static::make($defaults)->loadRequest($request, ...$keys);
    }

    /**
     * @param Request|null $request
     * @param string       ...$keys
     *
     * @return Collection
     */
    public function loadRequest(Request $request = null, string ...$keys): Collection
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
     * @param string      $string
     * @param string      $delimiter
     * @param string|null $delimeterLines = null
     *
     * @return Collection
     */
    public static function fromString(string $string, string $delimiter, string $delimeterLines = null): Collection
    {
        if ($delimeterLines !== null) {

            $items = explode($delimeterLines, $string);

            return static::make($items)
                         ->transform(function (string $value) use ($delimiter) {

                             return explode($delimiter, $value);
                         });
        }

        $items = explode($delimiter, $string);

        return new static($items);
    }

    /**
     * Import a CSV (comma separated value) string into a Collection.
     *
     * @param string $contents
     * @param string $eol       = PHP_EOL
     * @param string $delimiter = ','
     * @param string $enclosure = '"'
     * @param string $escape    = "\\"
     *
     * @return $this
     */
    public static function fromCsv(string $contents, string $eol = PHP_EOL, string $delimiter = ',',
        string $enclosure = '"', string $escape = "\\"): Collection
    {
        $items = [];

        foreach (explode($eol, $contents) as $line) {

            $line[] = static::fromCsvLine($line, $delimiter, $enclosure, $escape)->all();
        }

        return new static($items);
    }

    /**
     * Import a CSV (comma separated value) string into a Collection.
     *
     * @param string $contents
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     *
     * @return $this
     */
    public static function fromCsvLine(string $contents, string $delimiter = ',', string $enclosure = '"',
        string $escape = "\\"): Collection
    {
        $items = str_getcsv($contents, $delimiter, $enclosure, $escape);

        return new static($items);
    }
}
