<?php

declare(strict_types = 1);

namespace Tests\Support\Stubs;

use Tool\Traits\Properties\PropertyGettersTrait;

/**
 * Class PropertiesGetterStub
 *
 * @property-read bool $id
 * @property-read int  $nope
 */
class PropertiesGetterStub
{
    use PropertyGettersTrait;

    public function __construct()
    {
        $this->traitProperties = ['id' => 100];
    }

    protected function getId()
    {
        return false;
    }
}