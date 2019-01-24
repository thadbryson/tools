<?php

declare(strict_types = 1);

namespace Tests\Unit\Validation;

use Tool\Support\Validation\IsValid;
use function array_shift;

/**
 * Test IsValid class, wraps Assert class.
 */
class IsValidTest extends AssertTest
{
    /**
     * @dataProvider data
     *
     * @param string $method
     * @param        $value
     * @param mixed  ...$args
     */
    public function testNull(string $method, ...$args): void
    {
        array_shift($args);

        $this->assertFalse(IsValid::{$method}(null, ...$args), 'IsValid with NULL should be invalid: ' . $method);
    }

    /**
     * @dataProvider data
     * @dataProvider dataCustomMethods
     *
     * @param string $method
     * @param mixed  $value
     * @param mixed  ...$args
     */
    public function testValid(string $method, $value, ...$args): void
    {
        $this->assertTrue(IsValid::{$method}($value, ...$args), 'Method IsValid should be true: ' . $method);
    }

    /**
     * @dataProvider dataFalse
     * @dataProvider dataCustomMethodsFalse
     *
     * @param string $method
     * @param mixed  $value
     * @param mixed  ...$args
     */
    public function testInvalid(string $method, $value, ...$args): void
    {
        $this->assertFalse(IsValid::{$method}($value, ...$args), 'Method IsValid should be true: ' . $method);
    }
}
