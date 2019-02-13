<?php

declare(strict_types = 1);

namespace Tests\Unit\Validation\Exceptions;

use Tool\Validation\Exceptions\ValidationException;

/**
 * Class ValidationExceptionTest
 *
 * Exception for when Validation errors are thrown.
 */
class ValidationExceptionTest extends \Codeception\Test\Unit
{
    /**
     * @var ValidationException
     */
    private $exception;

    public function _before(): void
    {
        $this->exception = new ValidationException(['error1', 'error2', 'again'], 'Invalid stuff here', -5);
    }

    public function testProperties(): void
    {
        $this->assertEquals("Invalid stuff here

Errors: 
error1
- error2
- again
", $this->exception->getMessage());
        $this->assertEquals(-5, $this->exception->getCode());
    }

    public function testGetErrorBag(): void
    {
        $this->assertEquals(['error1', 'error2', 'again'], $this->exception->getErrorBag()->all());
    }

    public function testGetErrors(): void
    {
        $this->assertEquals([['error1'], ['error2'], ['again']], $this->exception->getErrors());
    }
}