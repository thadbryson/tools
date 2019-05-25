<?php

declare(strict_types = 1);

namespace Tool\Traits\Str;

use Ramsey\Uuid\Uuid;
use Tool\Str;

/**
 * Trait StaticMakeTrait
 *
 * @mixin Str
 */
trait StaticMakeTrait
{
    public static function implode(string $glue, array $parts, string $encoding = null): self
    {
        $str = implode($glue, $parts);

        return static::create($str, $encoding);
    }

    /**
     * Get a v4 UUID string.
     */
    public static function uuid(): self
    {
        $uuid = Uuid::uuid4()->toString();

        return new static($uuid);
    }

    public static function random(int $length, string $encoding = null,
        string $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): self
    {
        $random      = '';
        $charsLength = mb_strlen($chars, '8bit') - 1;

        for ($i = 0;$i < $length;$i++) {
            $random .= $chars[random_int(0, $charsLength)];
        }

        return static::make($random, $encoding)->shuffle();
    }

    public static function make($str, string $encoding = null): self
    {
        return static::create($str, $encoding);
    }

    public function clone(): self
    {
        return new Str((string) $this, $this->getEncoding());
    }
}