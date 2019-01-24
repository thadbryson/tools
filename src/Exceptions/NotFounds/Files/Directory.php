<?php
declare(strict_types = 1);

namespace Tool\Exceptions\NotFounds;

use Tool\Exceptions\NotFound;

class Directory extends NotFound
{
    /**
     * @inheritdoc
     */
    protected $title = 'Directory Not Found: %s';

    /**
     * Directory constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        parent::__construct($this->title, $path);
    }
}
