<?php
declare(strict_types = 1);

namespace Tool\Exceptions\Errors\NotImplemented;

use \Exception;

/**
 * Error 500 Exception
 */
class Method extends Exception
{
    /**
     * @inheritdoc
     */
    protected $code = 500;

    /**
     * Error title.
     *
     * @var string
     */
    protected $title = 'Error: Method %s on class %s has not been implemented.';

    /**
     * Error constructor.
     *
     * @param string $method
     * @param string $class
     * @param array  ...$args
     */
    public function __construct(string $method, string $class)
    {
        $message = sprintf($this->title, $method, $class);

        parent::__construct($message);
    }
}
