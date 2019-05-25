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
class PropertyGettersTraitTest extends \Codeception\Test\Unit
{

    public function testInvalidProperty(): void
    {

    }

    public function testHasGetter(): void
    {

    }
}
