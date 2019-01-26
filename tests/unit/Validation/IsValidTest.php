<?php

declare(strict_types = 1);

namespace Tests\Unit\Validation;

use Tool\Validation\IsValid;

/**
 * Test AssertRules class, wraps Assert class.
 */
class IsValidTest extends AssertTest
{
    /**
     * @dataProvider data
     */
    public function testNull(string $method, $value, ...$args): void
    {
        $this->assertFalse(IsValid::{$method}(null, ...$args), 'AssertRules with NULL should be invalid: ' . $method);
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
        $this->assertTrue(IsValid::{$method}($value, ...$args), 'Method AssertRules should be true: ' . $method);
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
        $this->assertFalse(IsValid::{$method}($value, ...$args), 'Method AssertRules should be true: ' . $method);
    }
}
