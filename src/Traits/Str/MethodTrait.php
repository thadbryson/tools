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
     *
     * @param string $append = ''
     * @return $this
     */
    public function getter(string $append = '')
    {
        return $this->codeMethod('get', $append);
    }

    /**
     * Get as a "setter" method name.
     *
     * @param string $append = ''
     * @return $this
     */
    public function setter(string $append = '')
    {
        return $this->codeMethod('set', $append);
    }

    /**
     * Get as a "hasser" method name.
     *
     * @param string $append = ''
     * @return $this
     */
    public function hasser(string $append = '')
    {
        return $this->codeMethod('has', $append);
    }

    /**
     * Get as an "isser" method name.
     *
     * @param string $append = ''
     * @return $this
     */
    public function isser(string $append = '')
    {
        return $this->codeMethod('is', $append);
    }

    /**
     * Get text as a method.
     *
     * @param string $prepend
     * @param string $append
     * @return $this
     */
    public function codeMethod(string $prepend, string $append)
    {
        return $this->replace('_', ' ')
            ->prepend($prepend . ' ')
            ->append(' ' . $append)
            ->titleize()
            ->lowerCaseFirst()
            ->replace(' ', '');
    }
}