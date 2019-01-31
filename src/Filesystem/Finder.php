<?php

declare(strict_types = 1);

namespace Tool\Filesystem;

use AppendIterator;
use Iterator;
use function iterator_to_array;
use SplFileInfo;
use Tool\Validation\Assert;

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

    public function setFileInfoClass(string $class): self
    {
        $this->fileInfoClass = Assert::isInstanceOf($class, SplFileInfo::class);

        return $this;
    }

    public function getIterator(): Iterator
    {
        $iterator = new AppendIterator;

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
}