<?php

declare(strict_types = 1);

namespace Tool\Paging;

use Tool\Traits\Properties\PropertyGetTrait;
use Tool\Validation\Assert;
use function floor;

/**
 * Class Pager
 *
 * Holds information about paging. Page number, limit, offset,
 * and what pages are available.
 *
 * @property-read int  $page
 * @property-read int  $offset
 * @property-read int  $limit
 * @property-read int  $isFirstPage
 * @property-read bool $hasPreviousPages
 * @property-read int  $numPreviousResults
 * @property-read int  $numPreviousPages
 */
class Pager
{
    use PropertyGetTrait;

    /**
     * Pager constructor.
     *
     * @param int $limit
     * @param int $offset
     */
    public function __construct(int $limit, int $offset)
    {
        Assert::greaterThan($limit, 0, '$limit must be greater than 0.');
        Assert::greaterThan($offset, -1, '$offset cannot be lower than 0.');

        $page = (int) floor($offset / $limit) + 1;

        // NOTE: limit is always above 0, checks in ::assertLimitOffset()
        $this->propertySet('page', $page)
            ->propertySet('limit', $limit)
            ->propertySet('offset', $offset)
            ->propertySet('isFirstPage', $page === 1)
            ->propertySet('hasPreviousPages', $offset > 0)
            ->propertySet('numPreviousResults', $offset > 0)
            ->propertySet('numPreviousPages', $offset > 0);
    }

    /**
     * Get PagerTotaled by providing a total value.
     *
     * @param int $total
     * @return PagerTotaled
     */
    public function total(int $total): PagerTotaled
    {
        return PagerTotaled::fromPager($this, $total);
    }
}
