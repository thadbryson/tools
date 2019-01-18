<?php

declare(strict_types = 1);

namespace Tool\Support;

use GuzzleHttp\Client;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;
use Tool\Validation\Assert;
use function http_build_query;
use function json_decode;

/**
 * Class HttpClient
 *
 * @method @static array callJsonGet(string $uri, array $options = [])
 */
class HttpClient extends Client
{
    /**
     * Global query parameters attached to every URI.
     *
     * @var array
     */
    protected $globalParams = [];

    public function __construct(string $baseUri = '', array $config = [])
    {
        $config['base_uri'] = $baseUri;

        parent::__construct($config);
    }

    /**
     * Get the base URI on this client (if any).
     *
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->getConfig()['base_uri'] ?? '';
    }

    /**
     * Set the global query parameters for all URIs.
     *
     * @param array $globalParams
     *
     * @return HttpClient
     */
    public function setGlobalQueryParameters(array $globalParams): self
    {
        $this->globalParams = $globalParams;

        return $this;
    }

    public function requestAsync($method, $uri = '', array $options = []): PromiseInterface
    {
        $uri = trim($uri, '& ') . http_build_query($this->globalParams);

        return parent::requestAsync($method, $uri, $options);
    }

    public static function toJson(ResponseInterface $response, int $options = 0): array
    {
        $contents = $response->getBody()->getContents();

        return json_decode($contents, true, 512, $options);
    }

    public function requestJson(string $method, string $uri = '', array $options = []): array
    {
        $response = $this->request($method, $uri, $options);

        return static::toJson($response);
    }

    public function jsonGet(string $uri, array $options = []): array
    {
        return $this->requestJson('GET', $uri, $options);
    }

    public function jsonPost(string $uri, array $options = []): array
    {
        return $this->requestJson('POST', $uri, $options);
    }

    public function jsonPut(string $uri, array $options = []): array
    {
        return $this->requestJson('PUT', $uri, $options);
    }

    public function jsonDelete(string $uri, array $options = []): array
    {
        return $this->requestJson('DELETE', $uri, $options);
    }

    /**
     * @param string $name
     * @param array  $arguments
     *
     * @return array|string
     */
    public static function __callStatic(string $name, array $arguments)
    {
        Assert::startsWith($name, 'call');

        $method = Str::make($name)->substr(4)->lowerCaseFirst()->toString();

        /** @var static $client */
        $client = Assert::methodExists(new static, $method);

        /** @var \GuzzleHttp\Psr7\Response|array $response */
        $response = $client->{$method}(...$arguments);

        if (is_array($response)) {
            return $response;
        }

        Assert::isInstanceOf($response, \GuzzleHttp\Psr7\Response::class);

        return $response->getBody()
                        ->getContents();
    }
}