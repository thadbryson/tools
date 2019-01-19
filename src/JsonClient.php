<?php

declare(strict_types = 1);

namespace Tool\Support;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use function http_build_query;
use function json_decode;

/**
 * Class JsonClient
 *
 */
class JsonClient
{
    /**
     * Global query parameters attached to every URI.
     *
     * @var array
     */
    protected $globalParams = [];

    /**
     * @var Client
     */
    protected $client;

    /**
     * Last URI called.
     *
     * @var string
     */
    protected $lastUri = '';

    /**
     * JsonClient constructor.
     *
     * @param string $baseUri = '' - Base URI for all API calls.
     * @param array  $config  = [] - Client configuration.
     */
    public function __construct(string $baseUri = '', array $config = [])
    {
        $config['base_uri'] = $baseUri;

        $this->client = new Client($config);
    }

    /**
     * Get the base URI on this client (if any).
     *
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->client->getConfig()['base_uri'] ?? '';
    }

    /**
     * Get last URI called by Client.
     *
     * @return string
     */
    public function getLastUri(): string
    {
        return $this->lastUri;
    }

    /**
     * Has a Request been made on this Client?
     *
     * @return bool
     */
    public function isUsed(): bool
    {
        return $this->getLastUri() !== '';
    }

    /**
     * Set the global query parameters for all URIs.
     *
     * @param array $globalParams
     *
     * @return JsonClient
     */
    public function setGlobalQueryParameters(array $globalParams): self
    {
        $this->globalParams = $globalParams;

        return $this;
    }

    /**
     * Get the global query Parameters for all URIs.
     *
     * @return array
     */
    public function getGlobalQueryParameters(): array
    {
        return $this->globalParams;
    }

    /**
     * Decode JSON from an HTTP Response.
     *
     * @param ResponseInterface $response
     * @param int               $options
     *
     * @return array
     */
    public static function jsonDecode(ResponseInterface $response, int $options = 0): array
    {
        $contents = $response->getBody()->getContents();

        return json_decode($contents, true, 512, $options);
    }

    public function get(string $uri, array $options = []): array
    {
        return $this->send('GET', $uri, $options);
    }

    public function post(string $uri, array $options = []): array
    {
        return $this->send('POST', $uri, $options);
    }

    public function put(string $uri, array $options = []): array
    {
        return $this->send('PUT', $uri, $options);
    }

    public function patch(string $uri, array $options = []): array
    {
        return $this->send('PATCH', $uri, $options);
    }

    public function delete(string $uri, array $options = []): array
    {
        return $this->send('DELETE', $uri, $options);
    }

    public function options(string $uri, array $options = []): array
    {
        return $this->send('OPTIONS', $uri, $options);
    }

    public function head(string $uri, array $options = []): array
    {
        return $this->send('HEAD', $uri, $options);
    }

    public function send(string $method, string $uri = '', array $options = []): array
    {
        if ($this->getGlobalQueryParameters() !== []) {
            $uri = trim($uri, '?& ') . '?' . http_build_query($this->globalParams);
        }

        $this->lastUri = trim($this->getBaseUri(), '/') . '/' . trim($uri, '/');

        $response = $this->client->request($method, $uri, $options);

        return static::jsonDecode($response);
    }
}
