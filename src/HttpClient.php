<?php

declare(strict_types = 1);

namespace Tool;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use function json_decode;

/**
 * Class JsonClient
 *
 */
class HttpClient
{
    use Traits\HttpClient\Headers,
        Traits\HttpClient\HttpMethods,
        Traits\HttpClient\Mappings,
        Traits\HttpClient\Options;

    public ?Client $client;

    public string $baseUri;

    public string $lastUri = '';

    public Request $request;

    public array $globalQuery = [];

    /**
     * HttpClient constructor.
     *
     * @param string $baseUri = '' - Base URI for all API calls.
     * @param array  $config  = [] - Client configuration.
     */
    public function __construct(string $baseUri = '', array $config = [])
    {
        $this->request = new Request;
        $this->baseUri = $baseUri;

        $config['base_uri'] = $baseUri;

        $this->client = new Client($config);
    }

    public static function jsonDecode(ResponseInterface $response, int $options = 0): array
    {
        $contents = $response->getBody()->getContents();

        return json_decode($contents, true, 512, $options) ?? [];
    }

    public function isUsed(): bool
    {
        return $this->lastUri !== '';
    }
}
