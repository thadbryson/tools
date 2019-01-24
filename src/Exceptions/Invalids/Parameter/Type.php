<?php
declare(strict_types = 1);

namespace Tool\Exceptions\Invalids\Parameter;

use Tool\Exceptions\Invalid;

/**
 * Method/Function parameter invalid type Exception.
 */
class Type extends Invalid
{
    /**
     * Type constructor.
     *
     * @param string $paramName
     * @param string ...$types
     */
    public function __construct(string $paramName, string ...$types)
    {
        $types = implode(', ', $types);

        parent::__construct('Parameter \$%s must be of type: %s', $paramName, $types);
    }
}
