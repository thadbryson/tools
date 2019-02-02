<?php

declare(strict_types = 1);

namespace Tests\Unit;

use Tool\Request;
use Tool\Str;
use function json_encode;

class RequestTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @dataProvider dataMobileAgents
     *
     * @param string $agent
     */
    public function testIsMobile(string $agent): void
    {
        $request = new Request([], [], [], [], [], ['HTTP_USER_AGENT' => $agent]);
        $this->assertTrue($request->isMobile());
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
        $request = new Request([], [], [], [], [], ['HTTP_USER_AGENT' => 'nope']);
        $this->assertFalse($request->isMobile());
    }

    public function testValidate(): void
    {
        $request = Request::create('/', 'POST', [
            'name'      => 'test',
            'id'        => '1',
            'is_active' => '1',
        ]);

        $result = $request->validate([
            'is_active' => 'string|in:2,3,4,5',
            'nah'       => 'required',
        ]);

        $this->assertFalse($result->isSuccess());
        $this->assertTrue($result->isFailure());

        $this->assertEquals([
            'is_active' => ['The selected is active is invalid.'],
            'nah'       => ['The nah field is required.'],
        ], $result->getErrors());
    }

    public function testCast(): void
    {
        $request = Request::create('/', 'POST', [
            'name'      => 'test',
            'id'        => '1',
            'is_active' => '1',
        ]);

        $casted = $request->cast([
            'id'        => 'int',
            'is_active' => 'bool',
        ]);

        $this->assertEquals([
            'name'      => 'test',
            'id'        => 1,
            'is_active' => true,
        ], $casted);
    }

    public function testAssertCsrf(): void
    {
        $token   = Str::uuid()->get();
        $request = Request::create('/', 'POST', ['_csrf' => $token]);

        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals($token, $request->input('_csrf'), json_encode($request->all()));

        // Valid.
        $request->assertCsrf('_csrf', $token);

        // CSRF does not match.
        $this->tester->expectException(new \InvalidArgumentException('Token is invalid.'), function () use ($request) {

            $request->assertCsrf('_csrf', 'nah');
        });

        // CSRF wrong key.
        $this->tester->expectException(new \InvalidArgumentException('Token is invalid.'), function ()
        use ($request, $token) {

            $request->assertCsrf('csrf', $token);
        });

        // Can't be an empty string.
        $this->tester->expectException(new \InvalidArgumentException('Token is invalid.'), function () use ($request) {

            $request->assertCsrf('_csrf', '');
        });
    }

    /**
     * @dataProvider dataMobileAgents
     */
    public function testIsCurrentMobile(string $agent): void
    {
        $_SERVER['HTTP_USER_AGENT'] = $agent;
        $this->assertTrue(Request::isCurrentMobile());

        $_SERVER['HTTP_USER_AGENT'] = 'nope';
        $this->assertFalse(Request::isCurrentMobile());
    }

    /**
     * @dataProvider dataMobileAgents
     *
     * @param string $agent
     */
    public function testIsAgentMobile(string $agent): void
    {
        $this->assertTrue(Request::isAgentMobile($agent));
    }

    public function testIsAgentMobileFalse(): void
    {
        $this->assertFalse(Request::isAgentMobile('nah'));
    }
}
