<?php

declare(strict_types = 1);

namespace Tool\Support;

use Throwable;
use function in_array;

/**
 * Response Class
 */
class Response extends \Illuminate\Http\Response
{
    /** @var int */
    protected $code;

    /**
     * Error codes that stay the same.
     * All other codes convert to 404.
     */
    public const CODE_NON_CHANGE = [200, 400, 403, 404];

    public static function fromException(Throwable $e): self
    {
        $message = $e->getMessage();
        $code    = $e->getCode();

        if (in_array($code, static::CODE_NON_CHANGE, $strict = false) === false) {
            $message = 'Unknown Error';
            $code    = 404;
        }

        $errors = [];

        if ($e instanceof ListException) {
            $errors = $e->getErrors();
        }

        $resposne = new static($message, $errors);
        $resposne->setCode($code);

        return $resposne;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function setJsonHeader(): self
    {
        $this->headers->set('Content-Type', 'application/json');

        return $this;
    }

    public static function getStatusText(int $code): ?string
    {
        return static::$statusTexts[$code] ?? null;
    }

    public static function isStatusCode(int $code): bool
    {
        return isset(static::$statusTexts[$code]);
    }
}
