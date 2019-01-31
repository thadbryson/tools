<?php

declare(strict_types = 1);

namespace Tests\Unit\Filesystem;

class FinderTest extends \Codeception\Test\Unit
{
    public function testConstruct(): void
    {
        // correct defaults are set on __construct()
    }

    public function testMake(): void
    {
        // make was set, correct directories passed in
    }

    public function testSetFileInfoClass(string $class): void
    {
        // Set custom FileInfo class
        // InvalidArgumentException thrown when trying to pass in non-existent class
        // InvalidArgumentException thrown when trying to pass in non \SplFileInfo class
    }

    public function testGetIterator(): void
    {
        // Iterator built with default Pathinfo class
        // Iterator built correctly with custom \SplFileInfo class
    }

    public function testToArray(): void
    {
        // iterator was returned to an array
    }
}
