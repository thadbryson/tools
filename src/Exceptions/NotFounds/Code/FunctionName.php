<?php
declare(strict_types = 1);

namespace Tool\Exceptions\NotFounds;

use Tool\Exceptions\NotFound;

class FunctionName extends NotFound
{
    /**
     * @inheritdoc
     */
    protected $title = 'Function Not Found: %s';

    /**
     * FunctionName constructor.
     *
     * @param string $functionName
     */
    public function __construct(string $functionName)
    {
        parent::__construct($this->title, $functionName);
    }
}
