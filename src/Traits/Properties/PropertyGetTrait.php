<?php

declare(strict_types = 1);

namespace Tool\Traits\Properties;

use InvalidArgumentException;
use function array_key_exists;

trait PropertyGetTrait
{
    /**
     * Hold object properties for magic __get()
     *
     * @var array
     */
    protected $traitProperties = [];

    /**
     * Read properties dynamically.
     *
     * @param string $property
     * @return mixed
     */
    public function __get(string $property)
    {
        return $this->propertyAssert($property);
    }

    protected function propertySet(string $name, $value)
    {
        $this->traitProperties[$name] = $value;

        return $this;
    }

    protected function propertyGet(string $name, $default = null)
    {
        return $this->traitProperties[$name] ?? $default;
    }

    protected function propertyHas(string $name)
    {
        return array_key_exists($name, $this->traitProperties) === true;
    }

    protected function propertyAssert(string $name)
    {
        if ($this->propertyHas($name) === false) {
            throw new InvalidArgumentException(sprintf('Property %s not found on class %s.', $name, static::class), 500);
        }

        return $this->propertyGet($name);
    }
}
