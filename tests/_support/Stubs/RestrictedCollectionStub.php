<?php

declare(strict_types = 1);

namespace Tests\Support\Stubs;

use Tool\Support\Collections\RestrictedCollection;

/**
 * Class RestrictedCollectionStub
 */
final class RestrictedCollectionStub extends RestrictedCollection
{
    protected $rules = [
        'id' => 'bool'
    ];
}