<?php

declare(strict_types = 1);

namespace Tests\Unit;

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
}
