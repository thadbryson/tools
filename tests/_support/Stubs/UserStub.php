<?php

declare(strict_types = 1);

namespace Tests\Support\Stubs;

/**
 * Class UserStub
 */
final class UserStub
{
    private $action = '';

    public function myMethod(): bool
    {
        return true;
    }

    public function myStaticMethod(): string
    {
        return 'yes';
    }

    public function save(): void
    {
        $this->action = 'save';
    }

    public function delete(): void
    {
        $this->action = 'delete';
    }

    public function getAction(): string
    {
        return $this->action;
    }
}
