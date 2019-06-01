<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\Properties;

use InvalidArgumentException;
use Tool\Paging\Pager;

/**
 * Class PropertyGetTraitTest
 *
 * TThe PropertyGetTrait is used throughout the code in classes.
 * I want to explicitly test the propertyAssert() method by calling
 * a non-existent property on a class that uses it.
 */
class PropertyGetTraitTest extends \Codeception\Test\Unit
{

    public function testPropertyAssert(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Property "nope" not found on decorator Tool\Paging\Pager.');

        $pager = new Pager(10, 0);
        $pager->nope;
    }
}
