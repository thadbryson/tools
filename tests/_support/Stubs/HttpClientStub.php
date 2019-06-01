<?php

declare(strict_types = 1);

namespace Tests\Support\Stubs;

use Tool\HttpClient;

class HttpClientStub extends HttpClient
{
    /**
     * HttpClient constructor.
     *
     * @param string $baseUri = '' - Base URI for all API calls.
     * @param array  $config  = [] - Client configuration.
     */
    public function __construct(string $baseUri = '', array $config = [])
    {
        parent::__construct($baseUri, $config);

        $this->client = null;
    }

    protected function internalSend(string $uri, string $method, array $options)
    {
        $this->lastUri = $this->prepareUri($uri);

        return [
            'base_url' => $this->request->getBaseUrl(),
            'uri'      => $this->lastUri,
            'method'   => $method,
            'options'  => $options
        ];
    }
}
