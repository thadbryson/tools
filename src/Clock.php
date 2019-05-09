<?php

declare(strict_types = 1);

namespace Tool;

use Carbon\CarbonInterval;
use DateTime;
use DateTimeInterface;
use Exception;
use function is_int;
use Tool\Validation\Assert;

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

    public function __construct($time = null, $tz = null)
    {
        if ($time instanceof DateTimeInterface) {
            $time = $time->getTimestamp();
        }

        if (is_int($time)) {
            $time = static::createFromTimestamp($time, $tz)->format('Y-m-d H:i:s');
        }

        parent::__construct($time, $tz);
    }

    /**
     * @alias of ::parse($time = null, $timezone = null)
     *
     * @return Clock
     */
    public static function make($time = null, $timezone = null): self
    {
        return static::parse($time, $timezone);
    }

    public static function makeOrNull($time = null, $timezone = null): ?self
    {
        try {
            return static::parse($time, $timezone);
        }
        catch (Exception $e) {
            return null;
        }
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

    public static function isAllDay($start, $end): bool
    {
        // Start and end date must be the same or end date can be 1 day after if ends on midnight.
        // Starts at midnight.
        // Ends at midnight to 1 second before midnight.

        $start = Clock::make($start);
        $end   = Clock::make($end);

        // Must start at midnight.
        // Must be same year and month. Can be a different day if ends at midnight on ending date.
        if ($start->format('H:i:s') !== '00:00:00' || $start->format('Y-m') !== $end->format('Y-m')) {
            return false;
        }

        $diffDays = $start->diff($end)->days;

        if ($diffDays === 1) {
            return $end->format('H:i:s') === '00:00:00';
        }
        elseif ($diffDays === 0) {
            return $end->format('H:i:s') === '23:59:59';
        }

        return false;
    }

    public static function isBetween($start, $end, $between): bool
    {
        $between = Clock::make($between)->format('Y-m-d H:i:s');
        $start   = Clock::make($start)->format('Y-m-d H:i:s');
        $end     = Clock::make($end)->format('Y-m-d H:i:s');

        return $start <= $between && $between <= $end;
    }

    public static function isNowBetween($start, $end): bool
    {
        return static::isBetween($start, $end, new DateTime);
    }

    public static function isBetweenTime($start, $end, $between): bool
    {
        $between = Clock::make($between)->format('Y-m-d H:i:s');
        $start   = Clock::make($start)->format('Y-m-d H:i:s');
        $end     = Clock::make($end)->format('Y-m-d H:i:s');

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

    public static function isNowBetweenTime($start, $end): bool
    {
        if ($start === null || $end === null) {
            return false;
        }

        return static::isBetweenTime(new Clock($start), new Clock($end), new DateTime);
    }

    public static function getDiff($start, $end = null): ?CarbonInterval
    {
        $end = $end ?? new static;

        if ($start instanceof DateTimeInterface === false || $end instanceof DateTimeInterface === false) {
            return new CarbonInterval(0);
        }

        $start = static::createFromDateTime($start);
        $end   = static::createFromDateTime($end);

        return $start->diffAsCarbonInterval($end);
    }

    public function isBefore($end): bool
    {
        return $this->getTimestamp() < static::make($end)->getTimestamp();
    }

    public function isAfter($end): bool
    {
        return $this->isBefore($end) === false;
    }

    /**
     * Determines if the instance is within the next week.
     *
     * @return bool
     */
    public function isCurrentWeekend(): bool
    {
        return $this->isSameWeek() && $this->isWeekend();
    }

    /**
     * Determines if the instance is within the next week.
     *
     * @return bool
     */
    public function isCurrentWeek(): bool
    {
        return $this->isSameWeek();
    }

    /**
     * Checks if the passed in date is the same exact hour as the instance´s hour.
     *
     * @param \Carbon\Carbon|\DateTimeInterface|null $date The instance to compare with or null to use the current date.
     *
     * @return bool
     */
    public function isSameWeek(DateTimeInterface $date = null): bool
    {
        return $this->isSameAs('W', $date);
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

    public function toDayString(bool $short = true): string
    {
        return $this->format($short ? 'D' : 'l');
    }

    public static function intervalDescription($startsAt, $endsAt, bool $isAllDay = false): string
    {
        $startsAt = Clock::makeOrNull($startsAt);
        $endsAt   = Clock::makeOrNull($endsAt);

        if ($startsAt === null || $endsAt === null) {
            return '';
        }

        // All day?
        if ($isAllDay === true) {
            return $startsAt->format('D, M j, Y');
        }

        // Default at description
        $display = $startsAt->format('D, M j, Y g:ia') . ' -<br>' . $endsAt->format('D, M j, Y g:ia');

        // Same day
        if ($startsAt->isSameDay($endsAt)) {
            $display = $startsAt->format('D, M j, Y g:ia') . ' - ' . $endsAt->format('g:ia');
        }

        return str_replace(':00', '', $display);
    }
}
