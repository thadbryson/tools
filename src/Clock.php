<?php

declare(strict_types = 1);

namespace Tool;

use DateTime;
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

    public static function isBetween(DateTimeInterface $start, DateTimeInterface $end, DateTimeInterface $between): bool
    {
        $between = $between->format('Y-m-d H:i:s');
        $start   = $start->format('Y-m-d H:i:s');
        $end     = $end->format('Y-m-d H:i:s');

        return $start <= $between && $between <= $end;
    }

    public static function isNowBetween(DateTimeInterface $start, DateTimeInterface $end): bool
    {
        return static::isBetween($start, $end, new DateTime);
    }

    public static function isBetweenTime(DateTimeInterface $start, DateTimeInterface $end, DateTimeInterface $between): bool
    {
        $between = $between->format('H:i:s');
        $start   = $start->format('H:i:s');
        $end     = $end->format('H:i:s');

        // Goes past midnigght?
        // Ex: end 3am, start 8pm
        if ($end < $start) {
            // start 22, end 04, time 00
            // start 22, end 04, time 23
            return $start <= $between || $between <= $end;
        }

        // start 05, end 10, time 08
        return $start <= $between && $between <= $end;
    }

    public static function isNowBetweenTime(DateTimeInterface $start, DateTimeInterface $end): bool
    {
        return static::isBetweenTime($start, $end, new DateTime);
    }

    /**
     * Get the "saving" format.
     */
    public function formatSave(): string
    {
        return $this->format(self::FORMAT_SAVE);
    }

    public static function getAvailableTimezones(): array
    {
        return timezone_identifiers_list();
    }

    public function toFormatedTimeString(): string
    {
        return $this->format('g:ia');
    }
}
