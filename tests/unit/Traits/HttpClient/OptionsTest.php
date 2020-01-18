<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\HttpClient;

use Tests\Support\Stubs\HttpClientStub;

class OptionsTest extends \Codeception\Test\Unit
{
    /**
     * @var HttpClientStub
     */
    private $client;

    public function _before(): void
    {
        $this->client = new HttpClientStub;
    }

    public function testGetOptionsInit(): void
    {
        $this->assertEquals([
            'query'   => [],
            'headers' => [
                'content-type' => [
                    0 => 'application/json',
                ],
            ],
        ], $this->client->getOptions());
    }

    public function testSetOptions(): void
    {
        $this->client->options = [
            'base_uri' => 'http://example.co',
            'json'     => [
                'id'   => 1,
                'name' => 'Test',
            ],
        ];

        $this->client->sendJson = true;
        $this->client->request->setMethod('POST');
        $this->client->request->request->set('some', 'key');

        $this->assertEquals([
            'query'    => [],
            'headers'  => [
                'content-type' => [
                    0 => 'application/json',
                ],
            ],
            'base_uri' => 'http://example.co',
            'json'     => [
                'id'   => 1,
                'name' => 'Test',
                'some' => 'key',
            ],
        ], $this->client->getOptions());
    }

    public function testAddOption(): void
    {
        $this->client->options['some'] = 'value';

        $this->assertEquals([
            'query'   => [],
            'headers' => [
                'content-type' => [
                    0 => 'application/json',
                ],
            ],
            'some'    => 'value',
        ], $this->client->getOptions());
    }
}
