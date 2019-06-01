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
            'headers' => []
        ], $this->client->getOptions());
    }

    public function testSetOptions(): void
    {
        $this->client->setOptions([
            'base_uri' => 'http://example.co',
            'json'     => [
                'id'   => 1,
                'name' => 'Test'
            ]
        ]);

        $this->client->sendJson();
        $this->client->request->setMethod('POST');
        $this->client->request->request->set('some', 'key');

        $this->assertEquals([
            'query'    => [],
            'headers'  => [],
            'base_uri' => 'http://example.co',
            'json'     => [
                'id'   => 1,
                'name' => 'Test'
            ]
        ], $this->client->getOptions());
    }

    public function testAddOption(): void
    {
        $this->client->addOption('some', 'value');

        $this->assertEquals([
            'query'   => [],
            'headers' => [],
            'some'    => 'value'
        ], $this->client->getOptions());
    }
}
