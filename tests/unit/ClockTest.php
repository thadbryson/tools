<?php

declare(strict_types = 1);

namespace Tests\Unit;

use function codecept_debug;
use DateTime;
use function timezone_abbreviations_list;
use Tool\Clock;

class ClockTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var \DateTime
     */
    protected $datetime;

    public function _before(): void
    {
        $this->datetime = new \DateTime('2000-01-03 12:15:45');
    }

    public function testConstruct(): void
    {
        $this->tester->assertClock($this->datetime, new Clock('2000-01-03 12:15:45'));
    }

    public function testStaticMakeOrNull(): void
    {
        $this->tester->assertClock($this->datetime, Clock::makeOrNull('2000-01-03 12:15:45'));
        $this->assertNull(Clock::makeOrNull('aaa'));
    }

    public function testStaticMake(): void
    {
        $this->tester->assertClock($this->datetime, Clock::make('2000-01-03 12:15:45'));
    }

    public function testCreateFromSaved(): void
    {
        $this->tester->assertClock($this->datetime, Clock::createFromSaved('2000-01-03 12:15:45'));
    }

    public function testCreateFromDateTime(): void
    {
        $this->tester->assertClock($this->datetime, Clock::createFromDateTime($this->datetime));
    }

    public function testFormatSave(): void
    {
        $this->assertEquals('2000-01-03 12:15:45', Clock::make('2000-01-03 12:15:45')->formatSave());
    }

    /**
     * @dataProvider dataIsDateBetween
     *
     * @param bool   $expected
     * @param string $start
     * @param string $end
     * @param string $datetime
     * @throws \Exception
     */
    public function testIsBetween(bool $expected, string $start, string $end, string $datetime): void
    {
        $this->assertEquals($expected, Clock::isDateBetween(new DateTime($start),new DateTime($end), new DateTime($datetime)));
    }

    public function dataIsDateBetween(): array
    {
        return [
            [true, '2000-01-01 00:00:00', '2005-01-01 00:00:00', '2000-01-01 00:00:00'],    // same start
            [true, '2000-01-01 00:00:00', '2005-01-01 00:00:00', '2005-01-01 00:00:00'],    // same end
            [true, '2000-01-01 00:00:00', '2002-06-22 00:00:00', '2001-12-31 00:00:00'],    // between
            [false, '2000-01-01 00:00:00', '2002-06-22 00:00:00', '1999-12-31 23:59:59'],    // 1 sec too soon
            [false, '2000-01-01 00:00:00', '2002-06-22 00:00:00', '2002-06-22 00:00:01'],    // 1 sec too late
            [false, '2000-01-01 00:00:00', '2002-06-22 00:00:00', '1995-06-22 00:00:01'],    // way too soon
            [false, '2000-01-01 00:00:00', '2002-06-22 00:00:00', '2020-06-22 00:00:01'],    // way too late
        ];
    }

    /**
     * @dataProvider dataIsTimeBetween
     *
     * @param bool   $expected
     * @param string $start
     * @param string $end
     * @param string $datetime
     * @throws \Exception
     */
    public function testIsTimeBetween(bool $expected, string $start, string $end, string $datetime): void
    {
        $this->assertEquals($expected, Clock::isTimeBetween(new DateTime($start),new DateTime($end), new DateTime($datetime)));
    }

    public function dataIsTimeBetween(): array
    {
        return [
            [true, '10:00:00', '11:00:00', '10:00:00'],    // same start
            [true, '00:00:00', '12:00:00', '12:00:00'],    // same end
            [true, '00:00:00', '22:00:00', '12:00:00'],    // between
            [true, '18:00:00', '04:00:00', '20:00:00'],    // ends past midnight into next day
            [false, '20:00:00', '03:00:00', '06:00:00'],    // ends past midnight into next day, but after ending
            [false, '13:00:00', '15:00:00', '12:59:59'],    // 1 sec too soon
            [false, '04:30:00', '07:00:00', '07:00:01'],    // 1 sec too late
            [false, '08:00:00', '17:00:00', '02:00:00'],    // way too soon
            [false, '04:00:00', '06:00:00', '09:00:00'],    // way too late
        ];
    }

    public function testIsAllDay(): void
    {
        $this->assertTrue(Clock::isAllDay('2000-01-01 00:00:00', '2000-01-02 00:00:00'));
        $this->assertTrue(Clock::isAllDay('2000-01-01 00:00:00', '2000-01-01 23:59:59'));

        $this->assertFalse(Clock::isAllDay('2000-01-01 00:00:00', '2000-01-03 00:00:00'));
        $this->assertFalse(Clock::isAllDay('2000-01-01 00:00:00', '2000-01-03 23:59:59'));
    }

    public function testIsDateBetween(): void
    {
        $this->assertTrue(Clock::isDateBetween('2000-01-01 00:00:00', '2000-01-02 00:00:00', '2000-01-01 12:10:00'));

        $this->assertFalse(Clock::isDateBetween('2000-01-01 00:00:00', '2000-01-01 23:59:59', '2000-01-02 00:00:00'));
    }

    public function testIsNowBetween(): void
    {
        $this->assertTrue(Clock::isNowBetween('2000-01-01 00:00:00', Clock::make()->addDay()));
        $this->assertFalse(Clock::isNowBetween('2000-01-01 00:00:00', Clock::make()->subDay()));
    }

    public function testIsNowBetweenTime(): void
    {
        $this->assertTrue(Clock::isNowBetweenTime('2000-01-01 00:00:00', Clock::make()->addDay()));
        $this->assertFalse(Clock::isNowBetweenTime('2000-01-01 00:00:00', Clock::make()->subDay()));

        $this->assertFalse(Clock::isNowBetweenTime('aaa', 'aaa'), 'Invalid datetimes');
    }

    public function testGetDiff(): void
    {
        $diff = Clock::getDiff('2000-01-01 00:00:00', '2001-03-05 10:20:30');
        $this->assertEquals('1 year 2 months 4 days 10 hours 20 minutes 30 seconds', $diff->forHumans());

        $diff = Clock::getDiff('aaa', 'aaa');
        $this->assertNull($diff);
    }

    public function testIsBefore(): void
    {
        $clock = new Clock();

        $this->assertTrue($clock->isBefore($clock->clone()->addDay()));
        $this->assertFalse($clock->isBefore($clock->clone()->subDay()));
    }

    public function testIsAfter(): void
    {
        $clock = new Clock();

        $this->assertTrue($clock->isAfter($clock->clone()->subDay()));
        $this->assertFalse($clock->isAfter($clock->clone()->addDay()));
    }

    public function testIsCurrentWeekend(): void
    {
        $isWeekend = Clock::make()->isWeekend();

        $this->assertEquals($isWeekend, Clock::make()->isCurrentWeekend());
        $this->assertFalse(Clock::make('2000-01-01 00:00:00')->isCurrentWeekend());
    }

    public function testIsSameWeek(): void
    {
        $clock = new Clock('2000-01-01');

        $this->assertTrue($clock->isSameWeek('2000-01-02'));
        $this->assertFalse($clock->isSameWeek('2000-02-02'));
        $this->assertFalse($clock->isSameWeek());
    }

    public function testGetAvailableTimezones(): void
    {
        $this->assertEquals(timezone_identifiers_list(), Clock::getAvailableTimezones());
    }

    public function testToFormatedTimeString(): void
    {
        $this->assertEquals('7:21pm', Clock::make('2000-01-01 19:21:23')->toFormatedTimeString());
    }

    public function testToDayString(): void
    {
        $this->assertEquals('Sat', Clock::make('2000-01-01 00:00:00')->toDayString());
        $this->assertEquals('Saturday', Clock::make('2000-01-01 00:00:00')->toDayString(false));
    }

    public function testIntervalDescription(): void
    {
        $desc = Clock::intervalDescription('aaa', 'aaa');
        $this->assertEquals('', $desc, 'Invalid dates entered');

        $desc = Clock::intervalDescription('aaa', '2010-06-22 00:00:00');
        $this->assertEquals('', $desc, 'Invalid starts at entered');

        $desc = Clock::intervalDescription('2010-06-22 00:00:00', 'aaa');
        $this->assertEquals('', $desc, 'Invalid ends at entered');

        $desc = Clock::intervalDescription('2000-01-01 00:00:00', '2001-05-06 00:00:00');
        $this->assertEquals('Sat, Jan 1, 2000 12am -<br>Sun, May 6, 2001 12am', $desc);

        $desc = Clock::intervalDescription('2000-01-01 00:00:00', '2000-01-01 00:00:00');
        $this->assertEquals('Sat, Jan 1, 2000 12am', $desc, 'Same second');

        $desc = Clock::intervalDescription('2000-01-01 00:00:00', '2000-01-01 00:00:59');
        $this->assertEquals('Sat, Jan 1, 2000 12am', $desc, 'Same minute');

        $desc = Clock::intervalDescription('2000-01-01 00:00:00', '2000-01-01 15:35:00');
        $this->assertEquals('Sat, Jan 1, 2000 12am - 3:35pm', $desc, 'Same day');

        $desc = Clock::intervalDescription('2000-01-01 00:00:00', '2001-05-06 00:00:00');
        $this->assertEquals('Sat, Jan 1, 2000 12am -<br>Sun, May 6, 2001 12am', $desc, 'different days');

        $desc = Clock::intervalDescription('2000-01-01 00:00:00', '2000-01-01 23:59:59');
        $this->assertEquals('Sat, Jan 1, 2000', $desc, 'All day');
    }
}
