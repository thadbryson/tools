<?php

declare(strict_types = 1);

namespace Tests\Support\Stubs;

use GuzzleHttp\Psr7\Response;
use Tool\HttpClient;
use function json_encode;

/**
 * Class HttpClientStub
 */
final class HttpClientStub extends HttpClient
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * JsonClient constructor.
     *
     * @param string $baseUri = '' - Base URI for all API calls.
     * @param array  $config  = [] - Client configuration.
     */
    public function __construct(string $baseUri = '')
    {
        $this->baseUri = $baseUri;
    }

    public function setTestResponse(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getTestResponse(): array
    {
        return $this->data;
    }

    public function getConfig(): array
    {
        return [
            'base_uri' => $this->baseUri,
        ];
    }

    public function request()
    {
        return new Response(200, [], json_encode($this->data));
    }
}
