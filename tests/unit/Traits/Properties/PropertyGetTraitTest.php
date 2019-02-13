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
        $this->expectExceptionObject(new InvalidArgumentException('Property nope not found on class Tool\Paging\Pager.', 500));

        $pager = new Pager(10, 0);
        $pager->nope;
    }
}
