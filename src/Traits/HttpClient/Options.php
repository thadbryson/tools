<?php

declare(strict_types = 1);

namespace Tool\Traits\HttpClient;

use Tool\HttpClient;

/**
 * Trait Options
 *
 * @mixin HttpClient
 */
trait Options
{
    protected $options = [];

    /**
     * Set Options for next Request.
     *
     * @param array $options
     * @return HttpClient
     */
    public function setOptions(array $options): HttpClient
    {
        $this->options = $options;

        return $this;
    }

    public function addOption(string $key, $option): HttpClient
    {
        $this->options[$key] = $option;

        return $this;
    }

    public function getOptions(): array
    {
        $options = $this->options;

        // Add all global query parameters.
        $this->request->query->add($this->getGlobalQuery());

        // Set form body to the Request. _POST data.
        // NOTE: some APIs won't handle the form body on a GET request.
        if ($this->request->isMethod('GET') === false && $this->request->request->count() > 0) {

            $formKey = $this->isJson() ?
                'json' :
                'form_params';

            $options[$formKey] = $this->request->request->all();
        }

        $options['query']   = $this->request->query->all();
        $options['headers'] = $this->request->headers->all();

        return $options;
    }
}
