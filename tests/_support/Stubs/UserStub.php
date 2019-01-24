<?php

declare(strict_types = 1);

namespace Tests\Support\Stubs;

/**
 * Class UserStub
 */
final class UserStub
{
    public function myMethod(): bool
    {
        return true;
    }

    public function myStaticMethod(): string
    {
        return 'yes';
    }
}
