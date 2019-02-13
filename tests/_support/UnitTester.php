<?php

declare(strict_types = 1);

use _generated\UnitTesterActions;
use Tool\Clock;
use Tool\Collection;
use Tool\Paging\Pager;
use Tool\Paging\PagerTotaled;
use Tool\Str;
use Tool\Validation\Result;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class UnitTester extends \Codeception\Actor
{
    use UnitTesterActions;

    /**
     * Define custom actions here
     */
    public function assertStr(Str $str, string $expected, string $encoding = null): self
    {
        $this->checkStr($str, $expected, $encoding)
            ->checkStr($str->clone(), $expected, $encoding);

        return $this;
    }

    public function assertRestrictedCollection($expected, Collection $coll, string $message = ''): self
    {
        $this->assertArr($expected, $coll, $message);

        $coll->assert();

        return $this;
    }

    public function assertArr($expected, $result, string $message = ''): self
    {
        if (is_object($expected) && method_exists($expected, 'toArray')) {
            $expected = $expected->toArray();
        }

        if (is_object($result) && method_exists($result, 'toArray')) {
            $result = $result->toArray();
        }

        $this->assertEquals($expected, $result, sprintf('%s, with array: %s', $message, json_encode($result)));

        return $this;
    }

    public function assertClock(DateTime $datetime, Clock $clock, string $message = ''): self
    {
        $this->assertEquals($datetime->format('Y-m-d H:i:s'), $clock->format('Y-m-d H:i:s'), $message);

        return $this;
    }

    public function assertValidationResult(Result $result, array $expectedErrors, string $message = ''): self
    {
        $this->assertEquals($expectedErrors, $result->getErrors(),
            'Expected errors: ' . json_encode($result->getErrors()) . ' ' . $message);

        $this->assertEquals($expectedErrors === [], $result->isSuccess(), 'Has errors isSuccess() should be TRUE');
        $this->assertEquals($expectedErrors !== [], $result->isFailure(), 'Has errors isSuccess() should be TRUE');

        return $this;
    }

    public function assertValidationSuccess(Result $result, string $message = ''): self
    {
        $this->assertEquals([], $result->getErrors(),
            'Expected errors: ' . json_encode($result->getErrors()) . ' ' . $message);

        $this->assertTrue($result->isSuccess(), 'Has errors isSuccess() should be TRUE');
        $this->assertFalse($result->isFailure(), 'Has errors isSuccess() should be TRUE');

        return $this;
    }

    public function assertPager(int $total, int $limit, int $offset, int $page, int $totalPages): void
    {
        $pager = new Pager($limit, $offset);

        $this->assertEquals($limit, $pager->limit, '->limit: ' . $pager->limit);
        $this->assertEquals($offset, $pager->offset, '->offset wrong: ' . $pager->offset);
        $this->assertEquals($page, $pager->page, '->page wrong: ' . $pager->page);
        $this->assertEquals($page === 1, $pager->isFirstPage, '->isFirstPage wrong: ' . $page);
        $this->assertEquals($offset > 0, $pager->hasPreviousPages, sprintf('->hasPreviousPages, page: %s', $page));
        $this->assertEquals($offset > 0, $pager->numPreviousResults, sprintf('->numPreviousResults, page: %s', $page));
        $this->assertEquals($offset > 0, $pager->numPreviousPages, sprintf('->numPreviousPages, page: %s', $page));

        $totaled = $pager->total($total);

        $this->assertEquals($total, $totaled->total, '->total wrong');
        $this->assertEquals($totalPages, $totaled->totalPages, '->totalPages wrong');
        $this->assertEquals($page === $totalPages, $totaled->isLastPage, '->isLastPage wrong');
        $this->assertEquals($page < $totalPages, $totaled->hasNextPages, '->hasNextPages wrong');
        $this->assertEquals($total - ($offset + $limit), $totaled->numNextResults, '->numNextResults wrong');
        $this->assertEquals($totalPages - $page, $totaled->numNextPages, '->numNextPages wrong');

        $totaled = new PagerTotaled($limit, $offset, $total);

        $this->assertEquals($total, $totaled->total, '->total wrong');
        $this->assertEquals($totalPages, $totaled->totalPages, '->totalPages wrong');
        $this->assertEquals($page === $totalPages, $totaled->isLastPage, '->isLastPage wrong');
        $this->assertEquals($page < $totalPages, $totaled->hasNextPages, '->hasNextPages wrong');
        $this->assertEquals($total - ($offset + $limit), $totaled->numNextResults, '->numNextResults wrong');
        $this->assertEquals($totalPages - $page, $totaled->numNextPages, '->numNextPages wrong');

        $totaled = PagerTotaled::fromPager($pager, $total);

        $this->assertEquals($total, $totaled->total, '->total wrong');
        $this->assertEquals($totalPages, $totaled->totalPages, '->totalPages wrong');
        $this->assertEquals($page === $totalPages, $totaled->isLastPage, '->isLastPage wrong');
        $this->assertEquals($page < $totalPages, $totaled->hasNextPages, '->hasNextPages wrong');
        $this->assertEquals($total - ($offset + $limit), $totaled->numNextResults, '->numNextResults wrong');
        $this->assertEquals($totalPages - $page, $totaled->numNextPages, '->numNextPages wrong');
    }

    protected function checkStr(Str $str, string $expected, string $encoding = null): self
    {
        $encoding = $encoding ?? \mb_internal_encoding();

        $this->assertInstanceOf(Str::class, $str, 'Must be object instance of ' . Str::class);

        $this->assertEquals($expected, $str->get());
        $this->assertEquals($expected, $str->__toString());
        $this->assertEquals($expected, (string) $str);

        $this->assertEquals($encoding, $str->getEncoding());

        return $this;
    }
}
