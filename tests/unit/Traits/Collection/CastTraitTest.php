<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\Collection;

use Tool\Cast;
use Tool\Collection;

class CastTraitTest extends \Codeception\Test\Unit
{
    public function testCastInteger(): void
    {
        $casted = Collection::make([
            '0', 0, '1', 1, 10, 'a'
        ])->castInteger();

        $this->assertEquals([0, 0, 1, 1, 10, 0], $casted->all());
    }

    public function testCastString(): void
    {
        $casted = Collection::make([
            '0', 0, '1', 1, 10, 'a'
        ])->castString();

        $this->assertEquals(['0', '0', '1', '1', '10', 'a'], $casted->all());
    }
}
