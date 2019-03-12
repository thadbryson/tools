<?php

declare(strict_types = 1);

namespace Tool\Traits\HttpClient;

use Tool\HttpClient;

/**
 * Trait QueryParameters
 *
 * @mixin HttpClient
 */
trait QueryParameters
{
    protected $globalQuery = [];

    public function getGlobalQuery(): array
    {
        return $this->globalQuery;
    }

    public function setGlobalQuery(array $query): self
    {
        $this->globalQuery = $query;

        return $this;
    }

    public function addGlobalQuery(string $key, $value): self
    {
        $this->globalQuery[$key] = $value;

        return $this;
    }
}
