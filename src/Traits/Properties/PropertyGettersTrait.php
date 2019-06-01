<?php

declare(strict_types = 1);

namespace Tool\Traits\Properties;

use InvalidArgumentException;
use Tool\StrStatic;
use function method_exists;

/**
 * Trait PropertyGettersTrait
 */
trait PropertyGettersTrait
{
    use PropertyGetTrait;

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
        $getter    = StrStatic::getter($name);
        $hasGetter = method_exists($this, $getter) === true;

        // No value or getter? - invalid call.
        if ($this->propertyHas($name) === false && $hasGetter === false) {
            throw new InvalidArgumentException(sprintf('Property "%s" was not found on class %s.', $name, static::class));
        }

        $value = $this->propertyGet($name);

        if ($hasGetter === true) {
            return $this->{$getter}($value);
        }

        return $value;
    }
}
