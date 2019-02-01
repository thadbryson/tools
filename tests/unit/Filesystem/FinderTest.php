<?php

declare(strict_types = 1);

namespace Tests\Unit\Filesystem;

use DateTime;
use InvalidArgumentException;
use SplFileInfo;
use Tool\Filesystem\Finder;
use Tool\Filesystem\Pathinfo;
use function dirname;

class FinderTest extends \Codeception\Test\Unit
{
    protected $directories;

    public function _before(): void
    {
        $this->directories = [
            dirname(__DIR__) . '/Filesystem',
            dirname(__DIR__) . '/Functions',
        ];
    }

    public function checkFinder(Finder $finder, array $directories): void
    {
        $this->assertEquals($directories, $finder->getDirectories(), 'Directories to search in.');
        $this->assertEquals(Pathinfo::class, $finder->getFileInfoClass(), 'Default \SplFileInfo child class.');
    }

    public function testConstruct(): void
    {
        $this->checkFinder(new Finder, []);
    }

    public function testStaticMake(): void
    {
        $finder = Finder::make(...$this->directories);

        $this->checkFinder($finder, $this->directories);
    }

    public function testSetFileInfoClass(): void
    {
        $this->assertEquals(Pathinfo::class, Finder::make()->getFileInfoClass(), 'Default \SplFileInfo child class.');

        $this->assertEquals(SplFileInfo::class, Finder::make()
            ->setFileInfoClass(SplFileInfo::class)
            ->getFileInfoClass(), 'Default \SplFileInfo child class.');

        $this->expectExceptionObject(new InvalidArgumentException('\DateTime is not a subclass of \SplFileInfo', 400));

        Finder::make()->setFileInfoClass(DateTime::class);
    }

    protected function checkIteratorAndArray(Finder $finder, string $splClass): void
    {
        $result = $finder->toArray();
        $count  = 0;

        /** @var Pathinfo $info */
        foreach ($finder->getIterator() as $index => $info) {

            /** @var SplFileInfo $match */
            $match = $result[$index] ?? null;

            $this->assertInstanceOf($splClass, $info, 'Iterator Path: ' . $index);
            $this->assertInstanceOf($splClass, $match, 'Array Path: ' . $index);

            $this->assertEquals($info->getPathname(), $match->getPathname(), 'filepaths found should match.');

            $count++;
        }

        $this->assertEquals(count($result), $count, 'Iterator / array counts did not match.');
    }

    public function testGetIterator(): void
    {
        $finder = Finder::make(...$this->directories);
        $this->checkIteratorAndArray($finder, Pathinfo::class);

        $finder->setFileInfoClass(SplFileInfo::class);
        $this->checkIteratorAndArray($finder, SplFileInfo::class);
    }
}
