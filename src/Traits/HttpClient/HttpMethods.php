<?php

declare(strict_types = 1);

namespace Tool\Traits\HttpClient;

use Symfony\Component\HttpFoundation\ParameterBag;
use Tool\Arr;
use Tool\HttpClient;
use Tool\Str;

/**
 * Trait HttpMethods
 *
 * @mixin HttpClient
 */
trait HttpMethods
{
    /**
     * Send an HTTP Request.
     *
     * @param string $uri   = '' - URI or path.
     * @param array  $post  = []
     * @param array  $query = []
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function send(string $method, string $uri = '', array $post = [], array $query = []): array
    {
        // Set method 1st so query and post parameters can be set.
        $this->request->setMethod($method);

        // Set _GET and _POST data.
        $this->request->query->add($query);
        $this->request->request->add($post);

        $options = $this->getOptions();

        $response = $this->internalSend($uri, $method, $options);
        $mappings = $this->mappings;

        // Reset the Request data.
        $this->mappings = [];
        $this->options  = [];

        $this->request->query   = new ParameterBag;
        $this->request->request = new ParameterBag;
        $this->request->headers = new ParameterBag;

        if ($this->sendJson === true) {
            $response = static::jsonDecode($response);
        }

        if ($mappings === []) {
            return $response;
        }

        if ($mappings['each'] === true) {

            return $mappings['only'] === true ?
                Arr::mapEachOnly($response, $mappings['mappings'], $mappings['keyMap']) :
                Arr::mapEach($response, $mappings['mappings'], $mappings['keyMap']);
        }

        return $mappings['only'] === true ?
            Arr::mapOnly($response, $mappings['mappings']) :
            Arr::map($response, $mappings['mappings']);
    }

    /**
     * @param string $uri
     * @param string $method
     * @param array  $options
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @codeCoverageIgnore
     */
    protected function internalSend(string $uri, string $method, array $options)
    {
        $this->lastUri = $this->prepareUri($uri);

        return $this->client->request($method, $this->lastUri, $options);
    }

    protected function prepareUri(string $uri): string
    {
        $baseUri = '';

        if (Str::make($uri)->startsWithAny(['http:', 'https:']) === false) {

            $baseUri = trim($this->getBaseUri(), '/');
        }

        $uri = $baseUri . '/' . trim($uri, '/');

        return trim($uri, '/');
    }

    public function get(string $uri = '/', array $query = []): array
    {
        return $this->send('GET', $uri, [], $query);
    }

    public function post(string $uri = '/', array $post = [], array $query = []): array
    {
        return $this->send('POST', $uri, $post, $query);
    }

    public function put(string $uri = '/', array $post = [], array $query = []): array
    {
        return $this->send('PUT', $uri, $post, $query);
    }

    public function patch(string $uri = '/', array $post = [], array $query = []): array
    {
        return $this->send('PATCH', $uri, $post, $query);
    }

    public function delete(string $uri = '/', array $post = [], array $query = []): array
    {
        return $this->send('DELETE', $uri, $post, $query);
    }

    public function options(string $uri = '/', array $post = [], array $query = []): array
    {
        return $this->send('OPTIONS', $uri, $post, $query);
    }

    public function head(string $uri = '/', array $post = [], array $query = []): array
    {
        return $this->send('HEAD', $uri, $post, $query);
    }
}
