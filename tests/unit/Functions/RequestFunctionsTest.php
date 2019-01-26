<?php

declare(strict_types = 1);

namespace Tests\Unit\Functions;

use function Tool\Functions\Request\is_current_mobile;
use function Tool\Functions\Request\is_mobile;

class RequestFunctionsTest extends \Codeception\Test\Unit
{
    /**
     * @dataProvider dataMobileAgents
     */
    public function testIsCurrentMobile(string $agent): void
    {
        $_SERVER['HTTP_USER_AGENT'] = $agent;

        $this->assertTrue(is_current_mobile());
    }

    public function testIsCurrentMobileFalse(): void
    {
        $_SERVER['HTTP_USER_AGENT'] = 'nope';

        $this->assertFalse(is_current_mobile());
    }

    /**
     * @dataProvider dataMobileAgents
     *
     * @param string $agent
     */
    public function testIsMobile(string $agent): void
    {
        $this->assertTrue(is_mobile($agent));
    }

    public function dataMobileAgents(): array
    {
        return [
            ['Mozilla/5.0 (iPhone; CPU iPhone OS 10_3_1 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/10.0 Mobile/14E304 Safari/602.1'],
            ['Mozilla/5.0 (Linux; U; Android 4.4.2; en-us; SCH-I535 Build/KOT49H) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30'],
            ['Mozilla/5.0 (Linux; Android 7.0; SM-G930V Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.125 Mobile Safari/537.36'],
        ];
    }

    public function testIsMobileAgentFalse(): void
    {
        $this->assertFalse(is_mobile('nah'));
    }
}
