<?php

declare(strict_types = 1);

namespace Tests\Unit\Filesystem;

use InvalidArgumentException;
use org\bovigo\vfs\vfsStream;
use Tool\Filesystem\Directory;
use Tool\Filesystem\Filesystem;
use function file_exists;
use function is_dir;
use function is_file;

class DirectoryTest extends \Codeception\Test\Unit
{
    use VfsStreamTrait;

    public function testConstruct(): void
    {
        $directory = Directory::make(vfsStream::url('root/dir1'));

        $this->assertEquals('vfs://root/dir1', $directory->getPathname());
    }

    public function testConstructExceptionWasFile(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('Filepath vfs://root/file.txt cannot be a file.', 400));

        Directory::make(vfsStream::url('root/file.txt'));
    }

    public function testConstructExceptionDirectoryDoesNotExist(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('Filepath vfs://root/no does not exist.', 400));

        Directory::make(vfsStream::url('root/no'));
    }

    public function testMakeEnsure(): void
    {
        $directory = Directory::makeEnsure(vfsStream::url('root/dir-yes'));

        $this->assertInstanceOf(Directory::class, $directory);
        $this->assertEquals('vfs://root/dir-yes', $directory->getPathname());
        $this->assertTrue(is_dir(vfsStream::url('root/dir-yes')));

        $directory = Directory::makeEnsure(vfsStream::url('root/dir-perms'), 0777);

        $this->assertEquals('vfs://root/dir-perms', $directory->getPathname());
        $this->assertTrue(is_dir(vfsStream::url('root/dir-yes')));
        $this->assertEquals('0777', Filesystem::chmod(vfsStream::url('root/dir-perms')));
    }

    public function testCopy(): void
    {
        $directory = Directory::make(vfsStream::url('root/dir1/dir-1-b/.hidden'));
        $copied = $directory->copy(vfsStream::url('root/was-hidden'));

        $this->assertEquals(vfsStream::url('root/was-hidden'), $copied->getPathname());

        $this->assertTrue(is_dir(vfsStream::url('root/dir1/dir-1-b/.hidden')));
        $this->assertTrue(is_file(vfsStream::url('root/dir1/dir-1-b/.hidden/template1.twig')));
        $this->assertTrue(is_file(vfsStream::url('root/dir1/dir-1-b/.hidden/template-not.not.twig')));
        $this->assertTrue(is_file(vfsStream::url('root/dir1/dir-1-b/.hidden/text.txt')));

        $this->assertTrue(is_dir(vfsStream::url('root/was-hidden')));
        $this->assertTrue(is_file(vfsStream::url('root/was-hidden/template1.twig')));
        $this->assertTrue(is_file(vfsStream::url('root/was-hidden/template-not.not.twig')));
        $this->assertTrue(is_file(vfsStream::url('root/was-hidden/text.txt')));
    }

    public function testMove(): void
    {
        $directory = Directory::make(vfsStream::url('root/dir1/dir-1-b/.hidden'));
        $moved = $directory->move(vfsStream::url('root/was-hidden2'));

        $this->assertEquals(vfsStream::url('root/was-hidden2'), $moved->getPathname());

        $this->assertFalse(file_exists(vfsStream::url('root/dir1/dir-1-b/.hidden')));
        $this->assertFalse(file_exists(vfsStream::url('root/dir1/dir-1-b/.hidden/template1.twig')));
        $this->assertFalse(file_exists(vfsStream::url('root/dir1/dir-1-b/.hidden/template-not.not.twig')));
        $this->assertFalse(file_exists(vfsStream::url('root/dir1/dir-1-b/.hidden/text.txt')));

        $this->assertTrue(is_dir(vfsStream::url('root/was-hidden2')));
        $this->assertTrue(is_file(vfsStream::url('root/was-hidden2/template1.twig')));
        $this->assertTrue(is_file(vfsStream::url('root/was-hidden2/template-not.not.twig')));
        $this->assertTrue(is_file(vfsStream::url('root/was-hidden2/text.txt')));
    }

    public function testDelete(): void
    {
        $directory = Directory::make(vfsStream::url('root/dir1'));

        $this->assertTrue(is_dir(vfsStream::url('root/dir1')));
        $this->assertTrue($directory->delete());
        $this->assertFalse(file_exists(vfsStream::url('root/dir1')));
    }

    public function testDeleteChildren(): void
    {
        $directory = Directory::make(vfsStream::url('root/dir1'));

        $this->assertTrue(is_dir(vfsStream::url('root/dir1')));
        $this->assertTrue($directory->deleteChildren());
        $this->assertTrue(is_dir(vfsStream::url('root/dir1')));

        $this->assertFalse(is_dir(vfsStream::url('root/dir1/dir-1-b/.hidden')));
        $this->assertFalse(is_file(vfsStream::url('root/dir1/dir-1-b/.hidden/template1.twig')));
        $this->assertFalse(is_file(vfsStream::url('root/dir1/dir-1-b/.hidden/template-not.not.twig')));
        $this->assertFalse(is_file(vfsStream::url('root/dir1/dir-1-b/.hidden/text.txt')));

        $this->assertEquals([], Filesystem::allFiles(vfsStream::url('root/dir1'), true));
        $this->assertEquals([], Filesystem::directories(vfsStream::url('root/dir1')));
    }
}
