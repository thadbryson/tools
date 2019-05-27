<?php

declare(strict_types = 1);

namespace Tool\Filesystem;

use ArrayIterator;
use Iterator;
use ReflectionClass;
use SplFileInfo;
use Tool\Collection;
use Tool\Validation\Assert;
use function iterator_to_array;

class Finder extends \Symfony\Component\Finder\Finder
{
    /**
     * \SplFileInfo class to return results to.
     *
     * @var string
     */
    protected $fileInfoClass = Pathinfo::class;

    public function __construct()
    {
        parent::__construct();

        $this
            ->ignoreDotFiles(false)
            ->ignoreVCS(false)
            ->sortByName();
    }

    public static function make(string ...$directories): self
    {
        return (new static)->in($directories);
    }

    public function getDirectories(): array
    {
        $secret = (new ReflectionClass(parent::class))->getProperty('dirs');
        $secret->setAccessible(true);

        return $secret->getValue($this);
    }

    public function getFileInfoClass(): string
    {
        return $this->fileInfoClass;
    }

    public function setFileInfoClass(string $class): self
    {
        $this->fileInfoClass = Assert::isSubclassOf($class, SplFileInfo::class);

        return $this;
    }

    public function getIterator(): Iterator
    {
        $iterator = new ArrayIterator;

        foreach (parent::getIterator() as $fileInfo) {

            $fileInfo = new $this->fileInfoClass($fileInfo->getPathname());

            $iterator->append($fileInfo);
        }

        return $iterator;
    }

    public function toArray(): array
    {
        return iterator_to_array($this->getIterator());
    }

    public function toCollection(): Collection
    {
        return new Collection($this->toArray());
    }

    protected function normalizeDir($dir)
    {
        $dir = rtrim($dir, '/' . \DIRECTORY_SEPARATOR);

        if (preg_match('#^s?ftp://#', $dir)) {
            $dir .= '/';
        }

        return $dir;
    }
}