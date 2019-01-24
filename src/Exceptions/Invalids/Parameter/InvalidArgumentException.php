<?php

declare(strict_types = 1);

namespace Tool\Exceptions\Invalids\Parameter;

use Tool\Exceptions\Invalid;

/**
 *
 */
class InvalidArgumentException extends Invalid
{
    public function __construct(string $argumentName, string $message)
    {
        parent::__construct('Argument "$%s" is invalid. %s', $argumentName, $message);
    }
}
