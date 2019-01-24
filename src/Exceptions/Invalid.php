<?php
declare(strict_types = 1);

namespace Tool\Exceptions;

/**
 * Invalid 400 Exception
 */
class Invalid extends Error
{
    /**
     * @inheritdoc
     */
    protected $code = 400;

    /**
     * Error title.
     *
     * @var string
     */
    protected $title = 'Invalid';
}
