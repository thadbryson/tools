<?php
declare(strict_types = 1);

namespace Tool\Exceptions\NotFounds;

use Tool\Exceptions\NotFound;

class ClassName extends NotFound
{
    /**
     * @inheritdoc
     */
    protected $title = 'Class Not Found: %s';

    /**
     * ClassName constructor.
     *
     * @param string $className
     */
    public function __construct(string $className)
    {
        parent::__construct($this->title, $className);
    }
}
