<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\Properties;

use InvalidArgumentException;
use Tests\Support\Stubs\PropertiesGetterStub;

class PropertyGettersTraitTest extends \Codeception\Test\Unit
{

    public function testInvalidProperty(): void
    {
        $getter = new PropertiesGetterStub;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Property "nope" was not found on class Tests\Support\Stubs\PropertiesGetterStub.');

        $getter->nope;
    }

    public function testHasGetter(): void
    {
        $getter = new PropertiesGetterStub;

        $this->assertEquals(false, $getter->id);
    }
}
