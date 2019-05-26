<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\Properties;

use Tests\Support\Stubs\PropertiesGetterStub;

class PropertyGettersTraitTest extends \Codeception\Test\Unit
{

    public function testInvalidProperty(): void
    {
        $getter = new PropertiesGetterStub;

        $this->expectException(\Assert\InvalidArgumentException::class);
        $this->expectExceptionMessage('Nope');

        $getter->nope;
    }

    public function testHasGetter(): void
    {
        $getter = new PropertiesGetterStub;

        $this->assertEquals(false, $getter->id);
    }
}
