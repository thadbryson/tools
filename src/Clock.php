<?php

declare(strict_types = 1);

namespace Tool\Support;

use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;
use function date_default_timezone_get;
use function is_string;

/**
 * Extend Carbon, which is a \DateTime extension.
 */
class Clock extends \Carbon\Carbon
{
    /**
     * 2018-02-01 12:01:59 America/New_York 1
     * year - month - date | hour : minutes : seconds | timezone | daylight savings
     *
     * @const string
     */
    public const FORMAT_SAVE = 'Y-m-d H:i:s e I';

    /**
     * Default timezone to use on all objects.
     *
     * @var DateTimeZone
     */
    protected static $defaultTimezone;

    /**
     * Force all objects to use same timezone.
     *
     * @var bool
     */
    protected static $forceTimezoneSame = false;

    // ***** Overridden properties.

    /**
     * First day of week. Sunday.
     *
     * @var int
     */
    protected static $weekStartsAt = self::SUNDAY;

    /**
     * Last day of week. Saturday.
     *
     * @var int
     */
    protected static $weekEndsAt = self::SATURDAY;

    /**
     * Datey constructor.
     *
     * @param DateTimeInterface|int|string|null $time     = null
     * @param \DateTimeZone|string|null         $timezone = null
     */
    public function __construct($time = null, $timezone = null)
    {
        $timezone = static::makeTimezone($timezone);

        parent::__construct($time, $timezone);

        // We're forcing the Timezone?
        // Now set the default DateTimeZone so the offset will be applied.
        // NOTE: We're only doing this in the constructor.
        // $this->setTimezone() is disabled if we're forcing the timezone.
        if (static::isForcingTimezone()) {
            parent::setTimezone(static::getDefaultTimezone());
        }
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

    /**
     * Create instance from a saved DateTime format string.
     *
     * @param string $value
     *
     * @return Clock
     */
    public static function fromSaved(string $value): self
    {
        return static::createFromFormat(self::FORMAT_SAVE, $value);
    }

    /**
     * @inheritdoc
     */
    public function formatSave(): string
    {
        return $this->format(self::FORMAT_SAVE);
    }

    /**
     * Set the Timezone. Force the default timezone if setting is enabled.
     *
     * @param DateTimeZone|string $value
     *
     * @return DateTimeInterface
     */
    public function setTimezone($value): DateTimeInterface
    {
        // Do nothing if we're forcing the timezone.
        if (static::isForcingTimezone()) {
            return $this;
        }

        $timezone = static::makeTimezone($value);

        // Now set the Timezone.
        return parent::setTimezone($timezone);
    }

    /**
     * Get number of seconds to different timezone.
     *
     * @param DateTimeZone|string $timezone
     */
    public function getTimezoneDiff($timezone): int
    {
        if ($timezone instanceof DateTimeZone || is_string($timezone) === false) {
            throw new InvalidArgumentException('\$timezone cannot be null');
        }

        return static::makeTimezone($timezone)->getOffset($this);
    }

    /**
     * Make a Timezone object without internal checks.
     *
     * @param DateTimeZone|string|null $value
     */
    public static function makeTimezone($value): DateTimeZone
    {
        // No timezone given: use default.
        $timezone = $value ?? static::getDefaultTimezone();

        // Make timezone now. If using default object it'll just return it.
        return parent::safeCreateDateTimeZone($timezone);
    }

    public static function isDefaultTimezone($timezone): bool
    {
        // Make sure timezone is valid.
        // An \InvalidArgumentException will be thrown if it is not.
        $timezone = static::makeTimezone($timezone);

        // NOTE: use name of the timezones. Not the offsets.
        //       Offsets could be different depending of daylight savings or could be the same for different
        //       timezones depending on the time of year.
        return $timezone->getName() === static::getDefaultTimezone()->getName();
    }

    public static function getDefaultTimezone(): DateTimeZone
    {
        // Not an object yet? - Set it.
        if (static::$defaultTimezone instanceof DateTimeZone === false) {
            // Not even set yet? - Use system default.
            $timezone = static::$defaultTimezone ?? date_default_timezone_get();

            static::setDefaultTimezone($timezone);
        }

        return static::$defaultTimezone;
    }

    public static function setDefaultTimezone($timezone): void
    {
        static::$defaultTimezone = static::makeTimezone($timezone);
    }

    public static function isForcingTimezone(): bool
    {
        return static::$forceTimezoneSame;
    }

    public static function setForceTimezone(bool $force): void
    {
        static::$forceTimezoneSame = $force;
    }
}
