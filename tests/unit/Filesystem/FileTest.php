<?php

declare(strict_types = 1);

namespace Tests\Unit\Filesystem;

use InvalidArgumentException;
use org\bovigo\vfs\vfsStream;
use Tool\Filesystem\File;
use function file_exists;
use function file_get_contents;
use function is_file;

class FileTest extends \Codeception\Test\Unit
{
    use VfsStreamTrait;

    public function testConstruct(): void
    {
        $file = File::make(vfsStream::url('root/file.txt'));

        $this->assertEquals('vfs://root/file.txt', $file->getPathname());
    }

    public function testConstructExceptionWasDirectory(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('Filepath vfs://root/dir1 cannot be a directory.', 400));

        File::make(vfsStream::url('root/dir1'));
    }

    public function testConstructExceptionFileDoesNotExist(): void
    {
        $this->expectExceptionObject(new InvalidArgumentException('Filepath vfs://root/nada.txt does not exist.', 400));

        File::make(vfsStream::url('root/nada.txt'));
    }

    public function testMakeEnsure(): void
    {
        $file = File::makeEnsure(vfsStream::url('root/ensure.md'));

        $this->assertInstanceOf(File::class, $file);
        $this->assertEquals('vfs://root/ensure.md', $file->getPathname());
        $this->assertEquals('', $file->getContents());

        $file = File::makeEnsure(vfsStream::url('root/ensure2.md'), 'NEW FILE');

        $this->assertEquals('vfs://root/ensure2.md', $file->getPathname());
        $this->assertEquals('NEW FILE', $file->getContents());
    }

    public function testSave(): void
    {
        $this->assertEquals(8, File::make(vfsStream::url('root/file.txt'))->save('saved???'));
    }

    public function testCopy(): void
    {
        $file = File::make(vfsStream::url('root/file.php'))
            ->copy(vfsStream::url('root/file-copy.php'));

        $this->assertEquals(vfsStream::url('root/file-copy.php'), $file->getPathname());

        $this->assertTrue(is_file(vfsStream::url('root/file.php')), 'Original file does not exist.');
        $this->assertTrue(is_file(vfsStream::url('root/file-copy.php')), 'Copied file does not exist.');

        $this->assertEquals('file php content', file_get_contents(vfsStream::url('root/file.php')), 'Original file should have same contents.');
        $this->assertEquals('file php content', $file->getContents(), 'Destination file should have copied contents.');
    }

    public function testMove(): void
    {
        $file = File::make(vfsStream::url('root/.some'))
            ->move(vfsStream::url('root/dir1/some.other.txt'));

        $this->assertEquals(vfsStream::url('root/dir1/some.other.txt'), $file->getPathname());

        $this->assertFalse(is_file(vfsStream::url('root/.some')), 'Original file should not exist after move()');
        $this->assertTrue(is_file(vfsStream::url('root/dir1/some.other.txt')), 'Moved file should not exist.');

        $this->assertEquals('hidden file', $file->getContents(), 'Should be same contents as when moved.');
    }

    public function testDelete(): void
    {
        $file = File::make(vfsStream::url('root/.some'));

        $this->assertTrue(is_file(vfsStream::url('root/.some')));
        $this->assertTrue($file->delete());
        $this->assertFalse(file_exists(vfsStream::url('root/.some')));
    }
}
