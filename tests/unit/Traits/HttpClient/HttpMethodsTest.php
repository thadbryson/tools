<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\HttpClient;

use Tests\Support\Stubs\HttpClientStub;

class HttpMethodsTest extends \Codeception\Test\Unit
{
    /**
     * @var HttpClientStub
     */
    private $client;

    public function _before(): void
    {
        $this->client = new HttpClientStub;
    }

    private function checkBasic(array $response, string $method, string $uri): void
    {
        $this->assertEquals([
            'base_url' => '',
            'uri'      => $uri,
            'method'   => $method,
            'options'  => [
                'query'   => [],
                'headers' => []
            ]
        ], $response);
    }

    public function testGet(): void
    {
        $response = $this->client->get('/');

        $this->checkBasic($response, 'GET', '');
    }

    public function testPost(): void
    {
        $response = $this->client->post();

        $this->checkBasic($response, 'POST', '');
    }

    public function testPut(): void
    {
        $response = $this->client->put('act');

        $this->checkBasic($response, 'PUT', 'act');
    }

    public function testPatch(): void
    {
        $response = $this->client->patch('_yes');

        $this->checkBasic($response, 'PATCH', '_yes');
    }

    public function testDelete(): void
    {
        $response = $this->client->delete('__here');

        $this->checkBasic($response, 'DELETE', '__here');
    }

    public function testOptions(): void
    {
        $response = $this->client->options('_yay_');

        $this->checkBasic($response, 'OPTIONS', '_yay_');
    }

    public function testHead(): void
    {
        $response = $this->client->head('');

        $this->checkBasic($response, 'HEAD', '');
    }
}
