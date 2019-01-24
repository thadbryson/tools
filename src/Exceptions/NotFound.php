<?php
declare(strict_types = 1);

namespace Tool\Exceptions;

/**
 * NotFound 404 Exception
 */
class NotFound extends Error
{
    /**
     * @inheritdoc
     */
    protected $code = 404;

    /**
     * Error title.
     *
     * @var string
     */
    protected $title = 'Not Found';
}
