<?php

declare(strict_types = 1);

namespace Tool\Exceptions\NotFounds;

use Tool\Exceptions\NotFound;

class MethodName extends NotFound
{
    /**
     * @inheritdoc
     */
    protected $title = 'Method Not Found: ';

    /**
     * MethodName constructor.
     *
     * @param string $className
     * @param string $methodName
     */
    public function __construct(string $className, string $methodName)
    {
        parent::__construct($this->title . ' %s on class %s', $methodName, $className);
    }
}
