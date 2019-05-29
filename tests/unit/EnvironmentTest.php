<?php

declare(strict_types = 1);

namespace Tests\Unit;

use Tests\Support\Stubs\EnvironmentStub;
use Tool\Environment;
use UnitTester;

class EnvironmentTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    private function assertEnvironment(bool $isWindows, bool $isLinux, bool $isMac): void
    {
        $this->assertEquals($isWindows, EnvironmentStub::isWindows());
        $this->assertEquals($isLinux, EnvironmentStub::isLinux());
        $this->assertEquals($isMac, EnvironmentStub::isMac());
    }

    public function testGetOs(): void
    {
        // Not using Stub so code in Environment::getOperatingSystem() gets tested.
        $this->assertEquals(PHP_OS === 'Windows', Environment::isWindows());
        $this->assertEquals(PHP_OS === 'Linux', Environment::isLinux());
        $this->assertEquals(PHP_OS === 'Free BSD', Environment::isMac());
    }

    public function testIsCommandLine(): void
    {
        $this->assertEquals(true, EnvironmentStub::isCommandLine());
    }

    public function testIsWindows(): void
    {
        EnvironmentStub::setOperatingSystem('WIN');
        $this->assertEnvironment(true, false, false);
    }

    public function testIsLinux(): void
    {
        EnvironmentStub::setOperatingSystem('LIN');
        $this->assertEnvironment(false, true, false);
    }

    public function testIsFreeBSD(): void
    {
        EnvironmentStub::setOperatingSystem('FRE');
        $this->assertEnvironment(false, false, true);
    }

    public function testIsMobile(): void
    {
        $this->assertFalse(EnvironmentStub::isMobile());

        $_SERVER['HTTP_USER_AGENT'] = 'windows ce';
        $this->assertTrue(EnvironmentStub::isMobile());

        $_SERVER['HTTP_USER_AGENT'] = 'nope';
        $this->assertFalse(EnvironmentStub::isMobile());
    }
}
