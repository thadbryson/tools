<?php

declare(strict_types = 1);

namespace Tests\Unit\Filesystem;

use InvalidArgumentException;
use org\bovigo\vfs\vfsStream;
use Tool\Filesystem\Directory;
use Tool\Filesystem\File;
use Tool\Filesystem\Pathinfo;
use function chmod;

class PathinfoTest extends \Codeception\Test\Unit
{
    use VfsStreamTrait {
        _before as beforeParent;
    }

    /**
     * @var File
     */
    private $file;

    /**
     * @var Directory
     */
    private $directory;

    public function _before(): void
    {
        $this->beforeParent();

        $this->file      = Pathinfo::make(vfsStream::url('root/file.txt'));
        $this->directory = Pathinfo::make(vfsStream::url('root/dir1'));
    }

    public function testConstruct(): void
    {
        $this->assertTrue($this->file->isFile());
        $this->assertTrue($this->directory->isDir());
    }

    public function testConstructDirectoryNotFound(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('Filepath vfs://root/no does not exist.', 400));

        Pathinfo::make(vfsStream::url('root/no'));
    }

    public function testConstructFileNotFound(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('Filepath vfs://root/notxt does not exist.', 400));

        Pathinfo::make(vfsStream::url('root/notxt'));
    }

    public function testGetContents(): void
    {
        $this->assertEquals('file text', $this->file->getContents());
        $this->assertEquals('', $this->directory->getContents(), 'Directories should just return an empty string.');
    }

    public function testIsHidden(): void
    {
        $this->assertTrue(Pathinfo::make(vfsStream::url('root/.some'))->isHidden());
        $this->assertTrue(Pathinfo::make(vfsStream::url('root/dir1/dir-1-b/.hidden'))->isHidden());

        $this->assertFalse($this->file->isHidden());
        $this->assertFalse($this->directory->isHidden());
    }

    public function testGetShort(): void
    {
        $directory = Pathinfo::make(vfsStream::url('root/dir1/dir-1-b/dir-1-b-a'));

        $this->assertTrue($directory->isDir());

        $this->assertEquals('dir-1-b/dir-1-b-a', $directory->getShort(vfsStream::url('root/dir1')));
        $this->assertEquals('dir-1-b/dir-1-b-a', $directory->getShort(vfsStream::url('root/dir1/')));

        $this->assertNull($directory->getShort(vfsStream::url('root/no')));
        $this->assertNull($directory->getShort(vfsStream::url('root/dir1~no')));
    }

    public function testHasExtension(): void
    {
        $this->assertTrue($this->file->hasExtension('txt'));
        $this->assertTrue($this->file->hasExtension('.txt'));

        $this->assertFalse($this->file->hasExtension('md'));

        $this->assertFalse($this->directory->hasExtension('txt'));
        $this->assertFalse($this->directory->hasExtension('dir1'));
    }

    public function testAssertReadable(): void
    {
        $this->file->assertReadable();
        $this->directory->assertReadable();

        $this->checkPermission(true, $this->file, 'Path "vfs://root/file.txt" was expected to be readable.');
        $this->checkPermission(true, $this->directory, 'Path "vfs://root/dir1" was expected to be readable.');
    }

    public function testAssertWritable(): void
    {
        $this->file->assertWritable();
        $this->directory->assertWritable();

        $this->checkPermission(false, $this->file, 'Path "vfs://root/file.txt" was expected to be writeable.');
        $this->checkPermission(false, $this->directory, 'Path "vfs://root/dir" was expected to be writeable.');
    }

    public function testAssertDirectory(): void
    {
        $this->directory->assertDirectory();
        $this->expectExceptionObject(new InvalidArgumentException('Filepath vfs://root/file.txt cannot be a file.', 400));

        Pathinfo::make(vfsStream::url('root/file.txt'))->assertDirectory();
    }

    public function testAssertFile(): void
    {
        $this->file->assertFile();
        $this->expectExceptionObject(new InvalidArgumentException('Filepath vfs://root/dir1 cannot be a directory.', 400));

        Pathinfo::make(vfsStream::url('root/dir1'))->assertFile();
    }

    private function checkPermission(bool $readable, Pathinfo $path, string $errorMessage): void
    {
        $this->expectExceptionObject(new InvalidArgumentException($errorMessage, 400));

        chmod($path->getPathname(), 0111);  // remove read access

        if ($readable) {
            $path->assertReadable();
        }
        else {
            $path->assertWritable();
        }
    }
}
