<?php

declare(strict_types = 1);

namespace Tool\Filesystem;

use ArrayIterator;
use Iterator;
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

    protected $searchDirectories = [];

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

    public function in($dirs): self
    {
        $resolvedDirs = [];

        foreach ((array) $dirs as $dir) {

            $glob = glob($dir, (\defined('GLOB_BRACE') ? GLOB_BRACE : 0) | GLOB_ONLYDIR);

            if (is_dir($dir)) {
                $resolvedDirs[] = $dir;
            }
            elseif ($glob) {
                $resolvedDirs = array_merge($resolvedDirs, array_map([$this, 'normalizeDir'], $glob));
            }
            else {
                throw new \InvalidArgumentException(sprintf('The "%s" directory does not exist.', $dir));
            }
        }

        $this->searchDirectories = array_merge($this->searchDirectories, $resolvedDirs);

        parent::in($dirs);

        return $this;
    }

    public function getDirectories(): array
    {
        return $this->searchDirectories;
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
}