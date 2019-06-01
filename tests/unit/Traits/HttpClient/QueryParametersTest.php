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
        $this->assertEquals([], $this->client->getGlobalQuery());
    }

    public function testSetGlobalQuery(): void
    {
        $this->client->setGlobalQuery([
            'id'   => 1,
            'name' => 'Test 1',
            'some' => 'other'
        ]);

        $this->assertEquals([
            'id'   => 1,
            'name' => 'Test 1',
            'some' => 'other'
        ], $this->client->getGlobalQuery());
    }

    public function testAddGlobalQuery(): void
    {
        $this->client
            ->addGlobalQuery('id', 7)
            ->addGlobalQuery('key', 'this');

        $this->assertEquals([
            'id'  => 7,
            'key' => 'this'
        ], $this->client->getGlobalQuery());
    }
}
