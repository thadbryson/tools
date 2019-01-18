<?php

declare(strict_types = 1);

namespace Tool\Support;

use DateTimeInterface;

/**
 * Extend Carbon, which is a \DateTime extension.
 */
class Clock extends \Carbon\Carbon
{
    /**
     * 2018-02-01 12:01:59 America/New_York 1
     * year - month - date | hour : minutes : seconds
     *
     * @const string
     */
    public const FORMAT_SAVE = self::DEFAULT_TO_STRING_FORMAT;

    protected static $weekStartsAt = self::SUNDAY;

    protected static $weekEndsAt = self::SATURDAY;

    /**
     * @alias of ::parse($time = null, $timezone = null)
     *
     * @return Clock
     */
    public static function make($time = null, $timezone = null): self
    {
        return static::parse($time, $timezone);
    }

    /**
     * Create instance from a saved DateTime format string.
     *
     * @param string $value
     *
     * @return Clock
     */
    public static function createFromSaved(string $value): self
    {
        return static::createFromFormat(self::FORMAT_SAVE, $value);
    }

    /**
     * Create from a DateTimeInterface object (convert).
     *
     * @param DateTimeInterface $datetime
     *
     * @return Clock
     */
    public static function createFromDateTime(DateTimeInterface $datetime): self
    {
        return new static($datetime->format('Y-m-d H:i:s'));
    }

    /**
     * Get the "saving" format.
     */
    public function formatSave(): string
    {
        return $this->format(self::FORMAT_SAVE);
    }
}
