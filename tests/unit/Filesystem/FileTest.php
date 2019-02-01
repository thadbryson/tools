<?php

declare(strict_types = 1);

namespace Tests\Unit\Filesystem;

class FileTest extends \Codeception\Test\Unit
{
    public function testConstruct(): void
    {
        // success: File object returned with path given
        // Exception: was a directory
        // Exception: file does not exist
    }

    public function testMakeEnsure(): void
    {
        // exists already:
    }

    public function testSave(): void
    {
        // write contents to path, return TRUE
        // could not write: return false
    }

    public function testCopy(): void
    {
        // copy path to target, return new File object for $target
        // could not copy: return null
    }

    public function testMove(): void
    {
        // copy path to target, return new File object for $target
        // could not copy: return null
    }

    public function testDelete(): void
    {
        // file deleted: return true
        // not deleted: return false
    }
}
