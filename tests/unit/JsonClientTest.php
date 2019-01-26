<?php

declare(strict_types = 1);

namespace Tests\Unit;

use GuzzleHttp\Psr7\Response;
use Tests\Support\Stubs\JsonClientStub;
use Tool\JsonClient;
use function json_encode;
use function strtolower;

/**
 * Class JsonClient
 */
class JsonClientTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var JsonClientStub
     */
    private $client;

    public function _before(): void
    {
        $this->client = new JsonClientStub('https://example.co');
        $this->client->setGlobalQueryParameters([
            'id'    => 1,
            'other' => 'some',
        ]);
    }

    public function testGetBaseUri(): void
    {
        $this->assertEquals('https://example.co', $this->client->getBaseUri());
    }

    public function testGetLastUri(): void
    {
        $this->assertEquals('', $this->client->getLastUri());

        $this->client->get('/some/here');

        $this->assertEquals('https://example.co/some/here?id=1&other=some', $this->client->getLastUri());
    }

    public function testHasBeenCalled(): void
    {
        $this->assertFalse($this->client->isUsed());

        $this->client->get('deal');

        $this->assertTrue($this->client->isUsed());
        $this->assertEquals('https://example.co/deal?id=1&other=some', $this->client->getLastUri());
    }

    public function testGetAndSetGlobalQueryParameters(): void
    {
        $this->client->setGlobalQueryParameters([
            'id'   => 4,
            'some' => 'mess',
        ]);

        $this->tester->assertArr([
            'id'   => 4,
            'some' => 'mess',
        ], $this->client->getGlobalQueryParameters());

        $this->client->send('PUT', 'yo');

        $this->assertEquals('https://example.co/yo?id=4&some=mess', $this->client->getLastUri());
    }

    public function testStaticJsonDecode(): void
    {
        $data = [
            'some'  => 'data',
            'stuff' => [2, 4, 6, 8],
        ];

        $responsee = new Response(200, [], json_encode($data));

        $this->tester->assertArr($data, JsonClient::jsonDecode($responsee));
    }

    /**
     * @dataProvider dataSend
     *
     * @param string $method
     * @param string $uri
     * @param array  $expected
     */
    public function testSend(string $method, string $uri, array $expected): void
    {
        $this->client->setTestResponse($expected);

        $expected['method']  = $method;
        $expected['uri']     = $uri;
        $expected['options'] = [];

        $this->tester->assertArr($expected, $this->client->send($method, $uri));

        $method = strtolower($method);

        // Call HTTP request method directly.
        $this->tester->assertArr($expected, $this->client->{$method}($uri));

        $uri = trim($uri, '/ ');

        $this->assertEquals("https://example.co/{$uri}?id=1&other=some", $this->client->getLastUri());
    }

    public function dataSend(): array
    {
        return [
            ['GET', 'some', ['id' => 1, 'form' => ['name' => 'Test', 'code' => 'AUG']]],
            ['POST', 'some', ['code' => '202', 'some' => 'other']],
            ['PATCH', 'some', ['type' => 1]],
            ['PUT', 'some', []],
            ['DELETE', 'some', []],
            ['HEAD', 'some', ['stuff' => null, 'what' => '???']],
            ['OPTIONS', 'some', []],
        ];
    }
}
