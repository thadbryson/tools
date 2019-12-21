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
    public array $options = [];

    public function getOptions(): array
    {
        $options = $this->options;

        // Add all global query parameters.
        $this->request->query->add($this->globalQuery);

        // Set form body to the Request. _POST data.
        // NOTE: some APIs won't handle the form body on a GET request.
        if ($this->request->isMethod('GET') === false && $this->request->request->count() > 0) {

            $formKey = $this->sendJson ?
                'json' :
                'form_params';

            $options[$formKey] = $this->request->request->all();
        }

        if ($this->sendJson) {
            $this->request->headers->set('Content-Type', 'application/json');
        }

        $options['query']   = $this->request->query->all();
        $options['headers'] = $this->request->headers->all();

        return $options;
    }
}
