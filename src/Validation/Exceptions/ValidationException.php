<?php

declare(strict_types = 1);

namespace Tool\Validation\Exceptions;

use Exception;
use Illuminate\Support\MessageBag;

/**
 * Class ValidationException
 *
 * Exception for when Validation errors are thrown.
 */
class ValidationException extends Exception
{
    /**
     * Validation errors.
     *
     * @var MessageBag
     */
    protected $errors;

    public function __construct(array $errors, string $message = null, int $code = 400)
    {
        parent::__construct($message ?? 'Validation errors have been found.', $code);

        $this->errors = new MessageBag($errors);
    }

    public function getErrorBag(): MessageBag
    {
        return $this->errors;
    }

    public function getErrors(): array
    {
        return $this->errors->messages();
    }
}