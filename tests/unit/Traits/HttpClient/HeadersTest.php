<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\HttpClient;

use Tests\Support\Stubs\HttpClientStub;

class HeadersTest extends \Codeception\Test\Unit
{
    /**
     * @var HttpClientStub
     */
    private $client;

    public function _before(): void
    {
        $this->client = new HttpClientStub;
    }

    public function testSetHeaders(): void
    {
        $this->client->setHeaders([
            'header1' => '1',
            'header2' => '2'
        ]);

        $this->assertEquals([
            'header1' => ['1'],
            'header2' => ['2']
        ], $this->client->request->header());
    }

    public function testSetAuthBearer(): void
    {
        $this->assertEquals([], $this->client->request->header());

        $this->client->setAuthBearer('911');

        $this->assertEquals([
            'authorization' => ['Bearer 911']
        ], $this->client->request->header());
    }
}
