<?php

declare(strict_types = 1);

namespace Tool\Traits\Str;

use Tool\Str;

/**
 * Trait JsonTrait
 *
 * @mixin Str
 */
trait JsonTrait
{
    /**
     * Perform standard json_decode of string.
     *
     * @return mixed
     */
    public function jsonDecode()
    {
        return $this->jsonDecodeOptions(true);
    }

    /**
     * Persom json_decode with options.
     *
     * @param bool $assoc
     * @param int  $options
     * @param int  $depth
     * @return mixed
     */
    public function jsonDecodeOptions(bool $assoc = false, int $options = 0, int $depth = 512)
    {
        $decoded = json_decode($this->str, $assoc, $depth, $options);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('String is not valid JSON: ' . $this->get());
        }

        return $decoded;
    }
}