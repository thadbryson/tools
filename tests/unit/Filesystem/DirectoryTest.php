<?php

declare(strict_types = 1);

namespace Tests\Unit\Filesystem;

class DirectoryTest extends \Codeception\Test\Unit
{
    public function testConstruct(): void
    {
        // assert passes with existing directory
        // Exception: is a file
        // Exception: nothing exists
    }

    public function testMakeEnsure(): void
    {
        // directory exists, nothing happens, Directory object made
        // directory created
    }

    public function testCopy(): void
    {
        // copy directory, given new Directory object
        // could not copy: return NULL
    }

    public function testMove(): void
    {
        // move directory, given new Directory object, existing is gone
        // could not move: return NULL, existing is gone
    }

    public function testDelete(): void
    {
        // true: directory deleted
        // false: could not be deleted
    }

    public function testDeleteChildren(): void
    {
        // true: children deleted, path is still there and a directory
        // false: children could not be deleted, path is still there and a directory
    }
}