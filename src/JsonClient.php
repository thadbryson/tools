<?php

declare(strict_types = 1);

namespace Tool;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
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
     * Send form bodies as JSON?
     *
     * @var bool
     */
    protected $sendJson = false;

    /**
     * Send requests asynchronously.
     *
     * @var bool
     */
    protected $sendAsync = false;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $baseUri;

    /**
     * Last URI called.
     *
     * @var string
     */
    protected $lastUri = '';

    protected $responseHandler;

    protected $exceptionHandler;

    /**
     * JsonClient constructor.
     *
     * @param string $baseUri = '' - Base URI for all API calls.
     * @param array  $config = [] - Client configuration.
     */
    public function __construct(string $baseUri = '', array $config = [])
    {
        $this->baseUri = $baseUri;

        $this->client = new Client($config);
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

        return json_decode($contents, true, 512, $options) ?? [];
    }

    public function sendJson()
    {
        $this->sendJson = true;

        return $this;
    }

    public function sendForm()
    {
        $this->sendJson = false;

        return $this;
    }

    public function sendAsynchronously()
    {
        $this->sendAsync = true;

        return $this;
    }

    public function clearAsynchronously()
    {
        $this->sendAsync = false;

        return $this;
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
     * Get last URI called by Client.
     *
     * @return string
     */
    public function getLastUri(): string
    {
        return $this->lastUri;
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
     * Send an HTTP Request.
     *
     * @param string $method - A valid HTTP method.
     * @param string $uri = '' - URI or path.
     * @param array  $form = []
     * @param array  $options = []
     *
     * @return array|PromiseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(string $method, string $uri = '', array $form = [], array $options = []): array
    {
        $uri     = $this->prepareUri($uri);
        $options = $this->prepareOptions($method, $form, $options);

        $this->lastUri = $uri;

        if ($this->sendAsync === false) {
            $response = $this->client->request($method, $uri, $options);

            return static::jsonDecode($response);
        }

        return $this->client
            ->requestAsync($method, $uri, $options)
            ->then(
                $this->responseHandler ?? function (ResponseInterface $res) {
                },
                $this->exceptionHandler ?? function (RequestException $res) {
                }
            );
    }

    public function setResponseHandler(callable $handler): self
    {
        $this->responseHandler = $handler;

        return $this;
    }

    public function clearResponseHandler(): self
    {
        $this->responseHandler = null;

        return $this;
    }

    public function setExceptionHandler(callable $handler): self
    {
        $this->exceptionHandler = $handler;

        return $this;
    }

    public function clearExceptionHandler(): self
    {
        $this->exceptionHandler = null;

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
     * Get the base URI on this client (if any).
     *
     * @return string
     */
    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    public function get(string $uri, array $options = []): array
    {
        return $this->send('GET', $uri, [], $options);
    }

    public function post(string $uri, array $form = [], array $options = []): array
    {
        return $this->send('POST', $uri, $form, $options);
    }

    public function put(string $uri, array $form = [], array $options = []): array
    {
        return $this->send('PUT', $uri, $form, $options);
    }

    public function patch(string $uri, array $form = [], array $options = []): array
    {
        return $this->send('PATCH', $uri, $form, $options);
    }

    public function delete(string $uri, array $form = [], array $options = []): array
    {
        return $this->send('DELETE', $uri, $form, $options);
    }

    public function options(string $uri, array $form = [], array $options = []): array
    {
        return $this->send('OPTIONS', $uri, $form, $options);
    }

    public function head(string $uri, array $form = [], array $options = []): array
    {
        return $this->send('HEAD', $uri, $form, $options);
    }

    protected function prepareUri(string $uri): string
    {
        if ($this->getGlobalQueryParameters() !== []) {
            $uri = trim($uri, '?& ') . '?' . http_build_query($this->globalParams);
        }

        $baseUri = '';

        if (Str::make($uri)
                ->removeLeft('/')
                ->startsWithAny(['http:', 'https:']) === false) {

            $baseUri = $this->getBaseUri();
        }

        $uri = trim($baseUri, '/') . '/' . trim($uri, '/');
        $uri = trim($uri, '/');

        return $uri;
    }

    protected function prepareOptions(string $method, array $form, array $options): array
    {
        // Set form body to the Request. _POST data.
        // NOTE: some APIs won't handle the form body on a GET request.
        if ($form !== [] && $method !== 'GET') {

            $key = $this->sendJson ? 'json' : 'form_params';

            $options[$key] = $form;
        }

        return $options;
    }
}
