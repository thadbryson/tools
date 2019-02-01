<?php

declare(strict_types = 1);

namespace Tests\Unit\Filesystem;

class PathinfoTest extends \Codeception\Test\Unit
{
    public function testConstruct(): void
    {
        // InvalidArgumentException thrown when filepath does not exist.
        // creates for existing directories
        // creates for existing files
    }

    public static function testMake(): void
    {
        // ::make() with string path
        // ::make() with \SplFileInfo var
        // ::make() with Pathinfo() var
    }

    public function testGetPermissionDescription(): void
    {
        // open perms - 0777
        // closed perms - 0655
        // for directory
        // for file
    }

    public function testGetContents(): void
    {
        // "" returned for a directory
        // contents returned for existing file
        // no file: FileNotFoundException() thrown
    }

    public function testIsHidden(): void
    {
        // hidden file
        // hidden directory
        // non-hidden file
        // non-hidden directory
    }

    public function testGetShort(): void
    {
        // short path after full path
        // no $parentDirectory apart of path
    }

    public function testHasExtension(): void
    {
        // has given extension
        // does not have extension
        // is directory: return false
    }

    public function testAssertReadable(): void
    {
        // throw InvalidArgumentException if not readable
        // is readable - no exception
    }

    public function testAssertWritable(): void
    {
        // throw InvalidArgumentException if not writable
        // is writable - no exception
    }

    public function testAssertDirectory(): void
    {
        // throw InvalidArgumentException if not directory
        // is directory - no exception
    }

    public function testAssertFile(): void
    {
        // throw InvalidArgumentException if not file
        // is file - no exception
    }
}
