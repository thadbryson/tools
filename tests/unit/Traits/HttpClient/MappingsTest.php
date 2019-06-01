<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\HttpClient;

use Tests\Support\Stubs\HttpClientStub;

class MappingsTest extends \Codeception\Test\Unit
{
    /**
     * @var HttpClientStub
     */
    private $client;

    public function _before(): void
    {
        $this->client = new HttpClientStub;
    }

    public function testGetMappingsDefault(): void
    {
        $this->assertEquals([], $this->client->getMappings());
    }

    private function checkMapping(array $mappings, bool $each, bool $only, string $keyMap = null): void
    {
        $this->assertEquals([
            'mappings' => $mappings,
            'each'     => $each,
            'only'     => $only,
            'keyMap'   => $keyMap
        ], $this->client->getMappings());
    }

    public function testSetMappings(): void
    {
        $mappings = [
            'key'       => 'id',
            'user.name' => 'name'
        ];

        $this->client->setMappings($mappings);
        $this->checkMapping($mappings, false, false);

        $this->client->setMappings($mappings);
    }

    public function testSetMappingsOnly(): void
    {
        $mappings = [
            'key'       => 'id',
            'user.name' => 'name'
        ];

        $this->client->setMappingsOnly($mappings);
        $this->checkMapping($mappings, false, true);

        $this->client->setMappingsOnly($mappings);
    }

    public function testSetMappingsMany(): void
    {
        $mappings = [
            'key'       => 'id',
            'user.name' => 'name'
        ];

        $this->client->setMappingsMany($mappings);
        $this->checkMapping($mappings, true, false);

        $this->client->setMappingsMany($mappings, 'key');
        $this->checkMapping($mappings, true, false, 'key');
    }

    public function testSetMappingsManyOnly(): void
    {
        $mappings = [
            'key'       => 'id',
            'user.name' => 'name'
        ];

        $this->client->setMappingsManyOnly($mappings);
        $this->checkMapping($mappings, true, true);

        $this->client->setMappingsManyOnly($mappings, 'someKey');
        $this->checkMapping($mappings, true, true, 'someKey');
    }

    public function testClearMappings(): void
    {
        $this->client->setMappingsManyOnly(['id' => 'some']);
        $this->client->clearMappings();

        $this->testGetMappingsDefault();
    }
}
