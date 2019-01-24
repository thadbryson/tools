<?php
declare(strict_types = 1);

namespace Tool\Exceptions\NotFounds;

use Tool\Exceptions\NotFound;

class File extends NotFound
{
    /**
     * @inheritdoc
     */
    protected $title = 'File Not Found: %s';

    /**
     * File constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        parent::__construct($this->title, $path);
    }
}
