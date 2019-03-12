<?php

declare(strict_types = 1);

namespace Tool\Traits\HttpClient;

use Symfony\Component\HttpFoundation\ParameterBag;
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
    public function send(string $method, string $uri = '', array $post = [], array $query = []): array
    {
        $this->request->query->add($query);
        $this->request->request->add($post);

        $this->lastUri = $this->prepareUri($uri);
        $options       = $this->prepareOptions();

        $response = $this->client->request($method, $this->lastUri, $options);

        // Reset the Request data.
        $this->request->query   = new ParameterBag;
        $this->request->request = new ParameterBag;
        $this->request->headers = new ParameterBag;
        $this->options          = [];

        return static::jsonDecode($response);
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

    public function get(string $uri, array $query = []): array
    {
        return $this->send('GET', $uri, [], $query);
    }

    public function post(string $uri, array $post = [], array $query = []): array
    {
        return $this->send('POST', $uri, $post, $query);
    }

    public function put(string $uri, array $post = [], array $query = []): array
    {
        return $this->send('PUT', $uri, $post, $query);
    }

    public function patch(string $uri, array $post = [], array $query = []): array
    {
        return $this->send('PATCH', $uri, $post, $query);
    }

    public function delete(string $uri, array $post = [], array $query = []): array
    {
        return $this->send('DELETE', $uri, $post, $query);
    }

    public function options(string $uri, array $post = [], array $query = []): array
    {
        return $this->send('OPTIONS', $uri, $post, $query);
    }

    public function head(string $uri, array $post = [], array $query = []): array
    {
        return $this->send('HEAD', $uri, $post, $query);
    }
}
