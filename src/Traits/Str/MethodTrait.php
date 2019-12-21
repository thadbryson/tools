<?php

declare(strict_types = 1);

namespace Tool\Traits\Str;

use Tool\Str;

/**
 * Trait MethodTrait
 *
 * @mixin Str
 */
trait MethodTrait
{
    /**
     * Get as a "getter" method name.
     */
    public function getter(string $append = ''): self
    {
        return $this->codeMethod('get', $append);
    }

    /**
     * Get as a "setter" method name.
     */
    public function setter(string $append = ''): self
    {
        return $this->codeMethod('set', $append);
    }

    /**
     * Get as a "hasser" method name.
     */
    public function hasser(string $append = ''): self
    {
        return $this->codeMethod('has', $append);
    }

    /**
     * Get as an "isser" method name.
     */
    public function isser(string $append = ''): self
    {
        return $this->codeMethod('is', $append);
    }

    /**
     * Get text as a method.
     */
    public function codeMethod(string $prepend, string $append): self
    {
        return $this->replace('_', ' ')
            ->prepend($prepend . ' ')
            ->append(' ' . $append)
            ->titleize()
            ->lowerCaseFirst()
            ->replace(' ', '');
    }
}