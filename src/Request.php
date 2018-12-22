<?php

declare(strict_types = 1);

namespace Tool\Support;

use Tool\Validation\Result;
use Tool\Validation\Validator;

/**
 * Request Class
 */
class Request extends \Illuminate\Http\Request
{
    /**
     * Collection of inputs from Request.
     */
    protected $collection = [];

    /**
     * Enabling HTTP method override by default.
     */
    protected static $httpMethodParameterOverride = true;

    /**
     * @param string $key
     * @param mixed  $default = null - Default value if none found.
     * @param string $cast    = null - Cast rule if any.
     *
     * @return self
     */
    public function collect(string $key, $default = null, string $cast = null): self
    {
        $this->collection[$key] = [
            'default' => $default,
            'cast'    => $cast,
        ];

        return $this;
    }

    public function toCollection(): Collection
    {
        $keys     = array_keys($this->collection);
        $defaults = Collection::make($this->collection)->pluck('default')->toArray();
        $casts    = Collection::make($this->collection)->pluck('cast')->whereNotIn('cast', [null])->toArray();

        $incoming = $this->all($keys);

        return Collection::make($defaults)
            ->merge($incoming)
            ->cast($casts);
    }

    public function validate(array $rules, array $messages = [], array $customAttributes = []): Result
    {
        $data             = $this->all();
        $data['_method']  = $this->getMethod();
        $data['_headers'] = $this->header();

        return Validator::validate($data, $rules, $messages, $customAttributes);
    }
}
