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
    public function setOptions(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function addOption(string $key, $option): self
    {
        $this->options[$key] = $option;

        return $this;
    }
}
