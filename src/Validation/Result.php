<?php

declare(strict_types = 1);

namespace Tool\Validation;

use Illuminate\Support\MessageBag;
use Tool\Exceptions\Error;
use Tool\Validation\Exceptions\ValidationException;

/**
 * Mapped Class
 */
class Result
{
    /**
     * @var MessageBag
     */
    protected $errors;

    /**
     * Mapped Constructor
     *
     * @param MessageBag $errors - Bag of errors
     */
    public function __construct(MessageBag $errors)
    {
        $this->errors = $errors;
    }

    public static function success(): self
    {
        return static::make([]);
    }

    /**
     * Make Mapped from array of error messages.
     *
     * @param string[] $errors - String of plain error messages.
     *
     * @return static
     */
    public static function make(array $errors): self
    {
        return new static(new MessageBag($errors));
    }

    /**
     * Throw an Exception if there are errors. Otherwise return the data that was validated.
     *
     * @param string $message = 'Invalid data found.' - Exception message to throw if any.
     * @param int    $code = 400  - Exception code to throw if any.
     *
     * @return bool
     * @throws ValidationException
     */
    public function assert(string $message = 'Invalid data found.', int $code = 400): bool
    {
        if ($this->errors->isNotEmpty()) {
            $this->throw($message, $code);
        }

        return true;
    }

    /**
     * @param string $message = 'Invalid data found.'
     * @param int    $code = 400
     *
     * @return void
     * @throws ValidationException
     */
    public function throw(string $message = 'Invalid data found.', int $code = 400): void
    {
        throw new ValidationException($this->getErrors(), $message, $code);
    }

    /**
     * Get error message strings separate by attribute.
     *
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors->messages();
    }

    /**
     * Get flattened array of error messages.
     *
     * @return string[]
     */
    public function getErrorsFlat(): array
    {
        return $this->errors->all();
    }

    /**
     * Get error bag.
     *
     * @return MessageBag
     */
    public function getMessageBag(): MessageBag
    {
        return $this->errors;
    }

    /**
     * Is there at least 1 error?
     *
     * @return bool
     */
    public function isFailure(): bool
    {
        return $this->isSuccess() === false;
    }

    /**
     * Are there no errors?
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        // Use object MessageBag because someone may add more messages to the bag.
        return $this->errors->count() === 0;
    }
}
