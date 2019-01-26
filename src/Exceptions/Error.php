<?php

declare(strict_types = 1);

namespace Tool\Exceptions;

use Tool\Arr;

/**
 * Error 500 Exception
 */
class Error extends \Exception
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
    protected $title = 'Error';

    /**
     * Any list of messages in the Exception.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Error constructor.
     *
     * @param string $message = ''
     * @param array  ...$args
     */
    public function __construct(string $message = '', ...$args)
    {
        $message = sprintf($this->title . ': ' . $message, ...$args);

        parent::__construct($message);
    }

    public static function make(string $message, int $code = 500): self
    {
        return new static($message, $code);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function offsetExists($dot): bool
    {
        return Arr::exists($this->items, $dot);
    }

    // For offsetGet() and offsetSet() can $dot be NULL?
    public function offsetGet($dot)
    {
        return Arr::get($this->items, $dot);
    }

    public function offsetSet($dot, $value): void
    {
        if ($dot === null) {
            $this->items[] = $value;
        } else {
            Arr::set($this->items, $dot, $value);
        }
    }

    public function offsetUnset($dot): void
    {
        Arr::forget($this->items, $dot);
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Set main Exception message.
     *
     * @param string  $message
     * @param mixed[] ...$args
     *
     * @return $this
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Set all sub messages at once.
     */
    public function setSubMessages(array $messages): self
    {
        $this->items = $messages;

        return $this;
    }

    public function getSubMessages(): array
    {
        return $this->items;
    }

    /**
     * Set Exception code.
     *
     * @param int $code
     *
     * @return Error
     */
    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Throw Exception ($this Exception) if there is a failure.
     *
     * @return array
     * @throws Error
     */
    public function assert(): array
    {
        if ($this->isFailure()) {
            $this->throw();
        }

        return $this->items;
    }

    public function isFailure(): bool
    {
        return $this->isSuccessful() === false;
    }

    /**
     * Is successful if there are no sub Exception messages.
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return count($this->items) === 0;
    }

    /**
     * Throw $this Exception
     */
    public function throw(): void
    {
        throw $this;
    }

    /**
     * Is main Exception not an empty string?
     *
     * @return bool
     */
    public function hasMainMessage(): bool
    {
        return $this->getMessage() !== '';
    }

    public function __toString()
    {
        return $this->getMessage();
    }
}
