<?php

declare(strict_types = 1);

namespace Tests\Support\Stubs;

use Tool\Support\JsonClient;

/**
 * Class JsonClientStub
 */
final class JsonClientStub extends JsonClient
{
    public function __construct(string $baseUri = '', array $config = [])
    {
        parent::__construct($baseUri, $config);

        $this->client = new HttpClientStub($baseUri);
    }

    public function setTestResponse(array $data): self
    {
        $this->client->setTestResponse($data);

        return $this;
    }

    public function send(string $method, string $uri = '', array $options = []): array
    {
        parent::send($method, $uri, $options);

        $data = $this->client->getTestResponse();

        $data['method']  = $method;
        $data['uri']     = $uri;
        $data['options'] = $options;

        return $data;
    }
}
