<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\Collection;

use Tool\Collection;

/**
 * Class FilterTraitTest
 */
class FilterTraitTest extends \Codeception\Test\Unit
{
    public function testFilterValue(): void
    {
        $coll = Collection::make([
            7, 1, 'a', 7, 1 => 10, 2 => 10, 'abc' => 6, 'def' => 10, true
        ])->filterValue(10);

        $this->assertEquals([
            1     => 10,
            2     => 10,
            'def' => 10
        ], $coll->toArray());
    }

    public function testFilterNull(): void
    {
        $coll = Collection::make([
            null, true, false, 1, 2, null, 3, 'key' => 'value', 'key2' => null
        ])->filterNull();

        $this->assertEquals([
            0      => null,
            5      => null,
            'key2' => null
        ], $coll->toArray());
    }
}
