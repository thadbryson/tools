<?php

declare(strict_types = 1);

namespace Tests\Unit;

use GuzzleHttp\Psr7\Response;
use Tests\Support\Stubs\HttpClientStub;
use Tool\HttpClient;
use function json_encode;

/**
 * Class HttpClient
 */
class HttpClientTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var HttpClientStub
     */
    private $client;

    public function _before(): void
    {
        $this->client = new HttpClientStub('https://example.co');
        $this->client->setGlobalQuery([
            'id'    => 1,
            'other' => 'some',
        ]);
    }

    public function testGetBaseUri(): void
    {
        $this->assertEquals('https://example.co', $this->client->getBaseUri());
    }

    public function testStaticJsonDecode(): void
    {
        $data = [
            'some'  => 'data',
            'stuff' => [2, 4, 6, 8],
        ];

        $responsee = new Response(200, [], json_encode($data));

        $this->tester->assertArr($data, HttpClient::jsonDecode($responsee));
    }

    public function testIsUsesd(): void
    {
        $this->assertFalse($this->client->isUsed());
    }

    public function testGetLastUri(): void
    {
        $this->assertEquals('', $this->client->getLastUri());
    }
}
