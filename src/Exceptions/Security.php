<?php
declare(strict_types = 1);

namespace Tool\Exceptions;

/**
 * Security 403 Exception
 */
class Security extends Error
{
    /**
     * @inheritdoc
     */
    protected $code = 403;

    /**
     * Error title.
     *
     * @var string
     */
    protected $title = 'Security Violation';
}
