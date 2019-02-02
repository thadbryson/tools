<?php

declare(strict_types = 1);

namespace Tests\Unit\Filesystem;

use org\bovigo\vfs\vfsStream;
use Tool\Filesystem\Filesystem;
use UnitTester;
use function is_dir;
use function is_file;

class FilesystemTest extends \Codeception\Test\Unit
{
    use VfsStreamTrait;

    /**
     * @var UnitTester
     */
    protected $tester;

    public function testStaticCall(): void
    {
        $this->assertTrue(Filesystem::isDirectory(vfsStream::url('root/dir1')), '::isDirectory() is a method, true expected');

        // 'bad' is not a method
        $this->expectExceptionMessage(sprintf('%s does not have method: %s', Filesystem::class, 'bad'));

        Filesystem::bad();
    }

    public function testSetPermissions(): void
    {
        $this->assertTrue(Filesystem::setPermissions(vfsStream::url('root/dir1'), 0777));
        $this->assertNull(Filesystem::setPermissions(vfsStream::url('root/nope'), 0777));

        $this->assertEquals('0777', Filesystem::getPermissionsDescription(vfsStream::url('root/dir1')));
    }

    public function testGetPermissionsDescription(): void
    {
        $this->assertEquals('0777', Filesystem::getPermissionsDescription(vfsStream::url('root/dir1')), 'Directory dir1/');
        $this->assertEquals('0666', Filesystem::getPermissionsDescription(vfsStream::url('root/file.txt')), 'File file.txt');

        $this->assertNull(Filesystem::getPermissionsDescription('nope'));
    }

    public function testSave(): void
    {
        $this->assertEquals(0, Filesystem::save(vfsStream::url('root/file-some.txt'), ''));
        $this->assertTrue(is_file(vfsStream::url('root/file-some.txt')), 'file-some.txt should exist now.');

        $this->assertEquals(5, Filesystem::save(vfsStream::url('root/file-some.txt'), '12345'));
        $this->assertTrue(is_file(vfsStream::url('root/file-some.txt')), 'file-some.txt should exist now.');
        // TODO: test can't be saved
    }

    public function testEnsureFile(): void
    {
        $this->assertFalse(is_file(vfsStream::url('root/file2.txt')), 'File should not exist for test: file.txt');
        $this->assertTrue(Filesystem::ensureFile(vfsStream::url('root/file2.txt')), 'File created: file2.txt');
        $this->assertTrue(is_file(vfsStream::url('root/file2.txt')), 'File should have been created.');

        $this->assertTrue(is_file(vfsStream::url('root/file.txt')), 'File should not exist for test: file.txt');
        $this->assertFalse(Filesystem::ensureFile(vfsStream::url('root/file.txt')), 'File exists, not created');
        $this->assertTrue(is_file(vfsStream::url('root/file.txt')), 'File should have been created.');
    }

    public function testEnsureDirectory(): void
    {
        $this->assertTrue(Filesystem::ensureDirectory(vfsStream::url('root/dir-ensure')), 'Directory created: dir-ensure');
        $this->assertFalse(Filesystem::ensureDirectory(vfsStream::url('root/dir1')), 'Directory exists, not created');

        $this->assertTrue(is_dir(vfsStream::url('root/dir-ensure')), 'Directory should have been created: dir-ensure/');
    }

    public function testIsHidden(): void
    {
        // files
        $this->assertTrue(Filesystem::isHidden(vfsStream::url('root/.some')), 'File exists, is hidden: .some');
        $this->assertTrue(Filesystem::isHidden(vfsStream::url('root/.does-not-exist')), 'File does not exist, is hidden: .some');

        $this->assertFalse(Filesystem::isHidden(vfsStream::url('root/file.php')), 'File exists, not hidden: file.php');
        $this->assertFalse(Filesystem::isHidden(vfsStream::url('root/nope.txt')), 'File does not exist, not hidden: nope.txt');

        // directories
        $this->assertTrue(Filesystem::isHidden(vfsStream::url('root/dir1/dir-1-b/.hidden')), 'Directory exists, not hidden: dir1/dir-1-b/.hidden');
        $this->assertTrue(Filesystem::isHidden(vfsStream::url('root/.yeah')), 'Directory does not exist, his hidden path: .yeah');

        $this->assertFalse(Filesystem::isHidden(vfsStream::url('root/dir1')), 'Directory exists, not hidden: dir1');
        $this->assertFalse(Filesystem::isHidden(vfsStream::url('root/nope')), 'Directory does not exist, not hidden: nope');
    }

    public function testGetShort(): void
    {
        $this->assertEquals('short', Filesystem::getShort('/home/path/short', '/home/path'));
        $this->assertEquals('short', Filesystem::getShort('/home/path/short/', '/home/path/'), 'Ending / should not matter.');

        $this->assertEquals('', Filesystem::getShort('/home/path/some-path/here', ''), 'Empty parent directory: return full path');

        $this->assertNull(Filesystem::getShort('/home/path-nope/short/', '/home/path/'), 'Short path not found..');
    }

    public function testHasExtension(): void
    {
        $this->assertTrue(Filesystem::hasExtension(vfsStream::url('file.txt'), 'txt'), 'file.txt with "txt"');
        $this->assertTrue(Filesystem::hasExtension(vfsStream::url('file.txt'), '.txt'), 'file.txt with ".txt"');

        $this->assertFalse(Filesystem::hasExtension(vfsStream::url('file.txt'), 'Txt'), 'file.txt with "Txt", case sensitive');
        $this->assertFalse(Filesystem::hasExtension(vfsStream::url('file.txt'), '.txT'), 'file.txt with ".txT" ,case sensitive');

        $this->assertTrue(Filesystem::hasExtension(vfsStream::url('file.txt'), 'Txt', false), 'file.txt with "Txt", not case-sensitive');
        $this->assertTrue(Filesystem::hasExtension(vfsStream::url('file.txt'), '.Txt', false), 'file.txt with ".Txt", not case-sensitive');

        $this->assertTrue(Filesystem::hasExtension(vfsStream::url('nope.txt'), 'txt'), 'File does not exist: "txt", still compares path string');
    }
}
