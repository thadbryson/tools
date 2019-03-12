<?php

declare(strict_types = 1);

namespace Tool;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use function json_decode;

/**
 * Class JsonClient
 *
 */
class HttpClient
{
    use Traits\HttpClient\HttpMethods,
        Traits\HttpClient\Headers,
        Traits\HttpClient\QueryParameters,
        Traits\HttpClient\Options;

    protected $client;

    protected $baseUri;

    protected $lastUri = '';

    public $request;

    /**
     * JsonClient constructor.
     *
     * @param string $baseUri = '' - Base URI for all API calls.
     * @param array  $config  = [] - Client configuration.
     */
    public function __construct(string $baseUri = '', array $config = [])
    {
        $this->client = new Client($config);

        $this->request = new Request;
        $this->baseUri = $baseUri;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public static function jsonDecode(ResponseInterface $response, int $options = 0): array
    {
        $contents = $response->getBody()->getContents();

        return json_decode($contents, true, 512, $options) ?? [];
    }

    public function isUsed(): bool
    {
        return $this->getLastUri() !== '';
    }

    public function getLastUri(): string
    {
        return $this->lastUri;
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }
}
