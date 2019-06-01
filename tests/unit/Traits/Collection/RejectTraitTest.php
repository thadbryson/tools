<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\Collection;

use Tool\Collection;

/**
 * Class RejectTraitTest
 */
class RejectTraitTest extends \Codeception\Test\Unit
{
    public function testRejectValue(): void
    {
        $coll = Collection::make([
            7, 1, 'a', 7, true
        ])->rejectValue(7);

        $this->assertEquals([1 => 1, 2 => 'a', 4 => true], $coll->toArray());
    }

    public function testRejectNull(): void
    {
        $coll = Collection::make([
            7, 1, 'a', null, null, 7, true, null, 'abcdef', null, 'other'
        ])->rejectNull();

        $this->assertEquals([7, 1, 'a', 5 => 7, 6 => true, 8 => 'abcdef', 10 => 'other'], $coll->toArray());
    }
}
