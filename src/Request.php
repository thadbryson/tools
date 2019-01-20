<?php

declare(strict_types = 1);

namespace Tool\Support;

use Tool\Validation\Result;
use Tool\Validation\Validator;
use function tool\support\request\is_mobile;

/**
 * Request Class
 */
class Request extends \Illuminate\Http\Request
{
    /**
     * Is the current Request coming from a mobile device?
     * From: http://detectmobilebrowsers.com/
     */
    public function isMobile(): bool
    {
        return is_mobile($this->userAgent());
    }

    public function validate(array $rules, array $messages = [], array $customAttributes = []): Result
    {
        return Validator::validate($this->all(), $rules, $messages, $customAttributes);
    }

    public function cast(array $casts): array
    {
        return Cast::all($this->all(), $casts);
    }

    public function assertCsrf(string $key, string $token): self
    {
        if ($token !== '' && $token !== $this->input($key)) {
            throw new \InvalidArgumentException('Token is invalid.');
        }

        return $this;
    }
}
