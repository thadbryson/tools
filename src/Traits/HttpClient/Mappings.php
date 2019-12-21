<?php

declare(strict_types = 1);

namespace Tool\Traits\HttpClient;

use Tool\HttpClient;

/**
 * Trait Mappings
 *
 * @mixin HttpClient
 */
trait Mappings
{
    /**
     * Configuration for mapping response data.
     */
    public array $mappings = [];

    public function setMappings(array $mappings): HttpClient
    {
        $this->mappings = [
            'mappings' => $mappings,
            'each'     => false,
            'only'     => false,
            'keyMap'   => null
        ];

        return $this;
    }

    public function setMappingsOnly(array $mappings): HttpClient
    {
        $this->mappings = [
            'mappings' => $mappings,
            'each'     => false,
            'only'     => true,
            'keyMap'   => null
        ];

        return $this;
    }

    public function setMappingsMany(array $mappings, string $keyMap = null): HttpClient
    {
        $this->mappings = [
            'mappings' => $mappings,
            'each'     => true,
            'only'     => false,
            'keyMap'   => $keyMap
        ];

        return $this;
    }

    public function setMappingsManyOnly(array $mappings, string $keyMap = null): HttpClient
    {
        $this->mappings = [
            'mappings' => $mappings,
            'each'     => true,
            'only'     => true,
            'keyMap'   => $keyMap
        ];

        return $this;
    }
}
