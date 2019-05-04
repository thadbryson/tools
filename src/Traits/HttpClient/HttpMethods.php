<?php

declare(strict_types = 1);

namespace Tool\Traits\HttpClient;

use Symfony\Component\HttpFoundation\ParameterBag;
use Tool\Arr;
use Tool\HttpClient;
use Tool\Str;
use function json_encode;

/**
 * Trait HttpMethods
 *
 * @mixin HttpClient
 */
trait HttpMethods
{
    /**
     * Configuration for mapping response data.
     *
     * @var array
     */
    protected $mappings = [];

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
    public function send(string $method, string $uri = '', array $post = [], array $query = []): array
    {
        // Set method 1st so query and post parameters can be set.
        $this->request->setMethod($method);

        // Set _GET and _POST data.
        $this->request->query->add($query);
        $this->request->request->add($post);

        $this->lastUri = $this->prepareUri($uri);
        $options       = $this->prepareOptions();

        $response = $this->client->request($method, $this->lastUri, $options);
        $mappings = $this->mappings;

        // Reset the Request data.
        $this->mappings         = [];
        $this->request->query   = new ParameterBag;
        $this->request->request = new ParameterBag;
        $this->request->headers = new ParameterBag;
        $this->options          = [];

        $response = static::jsonDecode($response);

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

    protected function prepareUri(string $uri): string
    {
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

    protected function prepareOptions(): array
    {
        $opts = $this->options;

        // Add all global query parameters.
        $this->request->query->add($this->getGlobalQuery());

        // Set form body to the Request. _POST data.
        // NOTE: some APIs won't handle the form body on a GET request.
        if ($this->request->isMethod('GET') === false && $this->request->request->count() > 0) {
            $opts[$this->sendJson ? 'json' : 'form_params'] = $this->request->request->all();
        }

        $opts['query']   = $this->request->query->all();
        $opts['headers'] = $this->request->headers->all();

        return $opts;
    }

    public function setMappings(array $mappings): self
    {
        $this->mappings = [
            'mappings' => $mappings,
            'each'     => false,
            'only'     => false,
            'keyMap'   => null
        ];

        return $this;
    }

    public function setMappingsOnly(array $mappings): self
    {
        $this->mappings = [
            'mappings' => $mappings,
            'each'     => false,
            'only'     => true,
            'keyMap'   => null
        ];

        return $this;
    }

    public function setMappingsMany(array $mappings, string $keyMap = null): self
    {
        $this->mappings = [
            'mappings' => $mappings,
            'each'     => true,
            'only'     => false,
            'keyMap'   => $keyMap
        ];

        return $this;
    }

    public function setMappingsManyOnly(array $mappings, string $keyMap = null): self
    {
        $this->mappings = [
            'mappings' => $mappings,
            'each'     => true,
            'only'     => true,
            'keyMap'   => $keyMap
        ];

        return $this;
    }

    public function clearMappings(): self
    {
        $this->mappings = [];

        return $this;
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
