<?php

declare(strict_types = 1);

namespace Tool\Support\Validation;

use Illuminate\Support\MessageBag;
use Tool\Exceptions\Error;
use function json_encode;

/**
 * Result Class
 */
class Result
{
    /**
     * @var MessageBag
     */
    protected $errors;

    /**
     * Result Constructor
     *
     * @param MessageBag $errors - Bag of errors
     */
    public function __construct(MessageBag $errors)
    {
        $this->errors = $errors;
    }

    /**
     * Make Result from array of error messages.
     *
     * @param string[] $errors - String of plain error messages.
     *
     * @return static
     */
    public static function fromArray(array $errors): self
    {
        return new static(
            new MessageBag($errors)
        );
    }

    public static function success(): self
    {
        return static::fromArray([]);
    }

    /**
     * Throw an Exception if there are errors. Otherwise return the data that was validated.
     *
     * @param string $message = null - Exception message to throw if any.
     * @param int    $code    = 400  - Exception code to throw if any.
     *
     * @return bool
     * @throws Error
     */
    public function assert(string $message = null, int $code = 400): bool
    {
        if ($this->errors->isNotEmpty()) {
            $this->throw($message ?? 'An Error has occured.', $code);
        }

        return true;
    }

    /**
     * @param string $message = null
     * @param int    $code    = 400
     *
     * @return void
     * @throws Error
     */
    public function throw(string $message = null, int $code = 400): void
    {
        $errors = $this->getErrors();

        throw Error::make(($message ?? '') . json_encode($errors), $code);
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
     * Are there no errors?
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        // Use object MessageBag because someone may add more messages to the bag.
        return $this->errors->count() === 0;
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
}
