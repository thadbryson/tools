<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\HttpClient;

use Tests\Support\Stubs\HttpClientStub;

class QueryParametersTest extends \Codeception\Test\Unit
{
    /**
     * @var HttpClientStub
     */
    private $client;

    public function _before(): void
    {
        $this->client = new HttpClientStub;
    }

    public function testGetGlobalQueryInit(): void
    {
        $this->assertEquals([], $this->client->globalQuery);
    }

    public function testAddGlobalQuery(): void
    {
        $this->client->globalQuery['id']  = 7;
        $this->client->globalQuery['key'] = 'this';

        $this->assertEquals([
            'id'  => 7,
            'key' => 'this'
        ], $this->client->globalQuery);
    }
}
