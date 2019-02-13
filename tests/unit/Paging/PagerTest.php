<?php

declare(strict_types = 1);

namespace Tests\Unit\Paging;

use InvalidArgumentException;
use Tool\Paging\Pager;
use UnitTester;

class PagerTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public function testConstructAssertLimitBelow1(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('$limit must be greater than 0.', 400));

        new Pager(0, 0);
    }

    public function testConstructAssertOffsetBelow0(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('$offset cannot be lower than 0.', 400));

        new Pager(1, -1);
    }

    /**
     * @dataProvider dataPager
     */
    public function testPager(int $total, int $limit, int $offset, int $page, int $totalPages): void
    {
        $this->tester->assertPager($total, $limit, $offset, $page, $totalPages);
    }

    public function dataPager(): array
    {
        return [
            [10, 1, 0, 1, 10],
            [20, 5, 18, 4, 4],
            [1000000, 100, 1000, 11, 10000],
            [75, 7, 43, 7, 11],
        ];
    }
}
