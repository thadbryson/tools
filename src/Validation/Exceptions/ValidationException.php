<?php

declare(strict_types = 1);

namespace Tool\Validation\Exceptions;

use Exception;
use Illuminate\Support\MessageBag;
use Tool\Environment;

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
        $this->errors = new MessageBag($errors);

        $message = $this->makeMessage($message);

        parent::__construct($message, $code);
    }

    public function getErrorBag(): MessageBag
    {
        return $this->errors;
    }

    public function getErrors(): array
    {
        return $this->errors->messages();
    }

    private function makeMessage(?string $message): string
    {
        $message = $message ?? 'Validation errors have been found.';

        // List errors if on command line and there are errors.
        if (Environment::isCommandLine() === true && $this->errors->count() > 0) {

            $message .= "\n\nErrors: \n" . implode("\n- ", $this->errors->all()) . "\n";
        }

        return $message;
    }
}
