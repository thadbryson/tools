<?php

declare(strict_types = 1);

namespace Tests\Unit;

use DateTime;
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
     * @dataProvider dataIsBetween
     *
     * @param bool   $expected
     * @param string $start
     * @param string $end
     * @param string $datetime
     * @throws \Exception
     */
    public function testIsBetween(bool $expected, string $start, string $end, string $datetime): void
    {
        $this->assertEquals($expected, Clock::isBetween(new DateTime($start),new DateTime($end), new DateTime($datetime)));
    }

    public function dataIsBetween(): array
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
     * @dataProvider dataIsBetweenTime
     *
     * @param bool   $expected
     * @param string $start
     * @param string $end
     * @param string $datetime
     * @throws \Exception
     */
    public function testIsBetweenTime(bool $expected, string $start, string $end, string $datetime): void
    {
        $this->assertEquals($expected, Clock::isBetweenTime(new DateTime($start),new DateTime($end), new DateTime($datetime)));
    }

    public function dataIsBetweenTime(): array
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
}
