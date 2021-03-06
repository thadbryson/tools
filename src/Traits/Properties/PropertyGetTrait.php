<?php

declare(strict_types = 1);

namespace Tool\Traits\Properties;

use InvalidArgumentException;
use Tool\StrStatic;
use function array_key_exists;
use function method_exists;

trait PropertyGetTrait
{
    /**
     * Hold object properties for magic __get()
     */
    protected array $traitProperties = [];

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
        $value = $this->traitProperties[$name] ?? $default;

        $getter = StrStatic::getter($name);

        if (method_exists($this, $getter)) {
            return $this->{$getter}($value);
        }

        return $value;
    }

    protected function propertyHas(string $name)
    {
        if (method_exists($this, StrStatic::getter($name))) {
            return true;
        }

        return array_key_exists($name, $this->traitProperties) === true;
    }

    protected function propertyAssert(string $name)
    {
        if ($this->propertyHas($name) === false) {
            throw new InvalidArgumentException(sprintf('Property "%s" not found on decorator %s.', $name, static::class), 500);
        }

        return $this->propertyGet($name);
    }
}
