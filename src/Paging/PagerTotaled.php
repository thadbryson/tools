<?php

declare(strict_types = 1);

namespace Tool\Paging;

/**
 * Class PagerTotaled
 *
 * Extends Pager object with total number of elements provided to
 * calculate how many pages are left.
 *
 * @property-read int  $total
 * @property-read int  $totalPages
 * @property-read bool $isLastPage
 * @property-read bool $hasNextPages
 * @property-read int  $numNextResults
 * @property-read int  $numNextPages
 */
class PagerTotaled extends Pager
{
    /**
     * PagerTotaled constructor.
     *
     * @param int $limit
     * @param int $offset
     * @param int $total
     */
    public function __construct(int $limit, int $offset, int $total)
    {
        parent::__construct($limit, $offset);

        $totalPages = (int) ceil($total / $this->limit);

        $this->propertySet('total', $total)
            ->propertySet('totalPages', $totalPages)
            ->propertySet('isLastPage', $this->page === $totalPages)
            ->propertySet('hasNextPages', $this->page < $totalPages)
            ->propertySet('numNextResults', $total - $offset - $limit)
            ->propertySet('numNextPages', $totalPages - $this->page);
    }

    /**
     * Build from a Pager object with total given.
     *
     * @param Pager $pager
     * @param int   $total
     * @return PagerTotaled
     */
    public static function fromPager(Pager $pager, int $total): PagerTotaled
    {
        return new static($pager->limit, $pager->offset, $total);
    }
}
