<?php

declare(strict_types = 1);

namespace Tests\Unit\Filesystem;

class FilesystemTest extends \Codeception\Test\Unit
{
    public function testStaticCall(): void
    {
        // MethodNotFoundException thrown when trying to call non-existent method statically.
        // Try calling a few methods.
    }

    public function testSetPermissions(): void
    {
        // permissions set correctly on existing file: returned true
        // permissions tried to be set on non-existent file: returned false
    }

    public function testGetPermissionsDescription(): void
    {
        // correct perms found for existing file
        // correct perms found for existing directory

        // non-existing path: null returned
    }

    public function testSave(): void
    {
        // saved file correctly, contents set right, number bytes written returned

        // could not be saved
    }

    public function testEnsureFile(): void
    {
        // already exists: returned false
        // does not exist, created: returned true

        // doesn't exist, not created, Exception thrown
    }

    public function testEnsureDirectory(): void
    {
        // already exists: returned false
        // does not exist, created: returned true

        // doesn't exist, not created, Exception thrown
    }

    public function testIsHidden(): void
    {
        // hidden file: true
        // file, not hidden: false

        // hidden directory: true
        // file, not hidden: false
    }

    public function testGetShort(): void
    {
        // parent is apart of path: returned shorted path, no front /
        // not apart of path: return null
        // parent directory is empty string: return full path
    }

    public function testHasExtension(): void
    {
        // true: has extension
        // true: has extension with beginning "."
        // true: has with different case - not sensitive
        // false: does not have extension
    }
}
