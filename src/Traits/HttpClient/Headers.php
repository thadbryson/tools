<?php

declare(strict_types = 1);

namespace Tool\Traits\HttpClient;

use Tool\HttpClient;

/**
 * Trait Headers
 *
 * @mixin HttpClient
 */
trait Headers
{
    /**
     * Send form bodies as JSON?
     */
    public bool $sendJson = true;

    /**
     * Set Headers for next Request.
     *
     * @param array $headers
     * @return HttpClient
     */
    public function setHeaders(array $headers): HttpClient
    {
        $this->request->headers->add($headers);

        return $this;
    }

    public function setAuthBearer(string $token): HttpClient
    {
        $this->request->headers->set('authorization', 'Bearer ' . $token);

        return $this;
    }
}
