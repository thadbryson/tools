<?php

declare(strict_types = 1);

namespace Tool;

use Tool\Traits\Properties\PropertyGettersTrait;
use function array_keys;

/**
 * Class Decorator
 *
 * Used to access data from a Model. Easier to get attributes
 * this way.
 */
class Decorator implements Interfaces\Decorator
{
    use PropertyGettersTrait;

    /**
     * Default values for this Decorator.
     *
     * @var array
     */
    protected $defaults = [];

    /**
     * Decorator constructor.
     *
     * @param array $properties
     */
    public function __construct(array $properties)
    {
        $this->traitProperties = Arr::defaults($properties, $this->defaults);
    }

    public static function make(array $properties): self
    {
        return new static($properties);
    }

    public function toArray(): array
    {
        $data = [];

        foreach (array_keys($this->traitProperties) as $key) {
            $data[$key] = $this->__get((string) $key);
        }

        return $data;
    }

    public function toArrayOriginal(): array
    {
        return $this->traitProperties;
    }
}
