<?php

declare(strict_types = 1);

namespace Tool\Interfaces;

/**
 * Interface Decorator
 *
 * Must be implemented on all Decorator classes.
 */
interface Decorator
{
    /**
     * Constructor for Decorator object.
     *
     * @param array $properties
     */
    public function __construct(array $properties);

    /**
     * Magic get for accessing properties.
     *
     * @param string $name
     * @return mixed
     */
    public function __get(string $name);
}
