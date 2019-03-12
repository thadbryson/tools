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
     *
     * @var bool
     */
    protected $sendJson = false;

    /**
     * Set Headers for next Request.
     *
     * @param array $headers
     * @return HttpClient
     */
    public function setHeaders(array $headers): self
    {
        $this->request->headers->add($headers);

        return $this;
    }

    public function setAuthBearer(string $token): self
    {
        $this->request->headers->set('Authorization', 'Bearer ' . $token);

        return $this;
    }

    public function sendJson()
    {
        $this->sendJson = true;

        return $this;
    }

    public function clearJson()
    {
        $this->sendJson = false;

        return $this;
    }
}
