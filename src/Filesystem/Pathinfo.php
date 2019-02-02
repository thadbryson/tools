<?php

declare(strict_types = 1);

namespace Tool\Filesystem;

use SplFileInfo;
use Tool\Validation\Assert;

class Pathinfo extends SplFileInfo
{
    public function __construct(string $path)
    {
        Assert::filepath($path, 'Filepath %s does not exist.');

        parent::__construct($path);
    }

    /**
     * @param string|SplFileInfo $path
     * @return Pathinfo
     */
    public static function make($path)
    {
        if ($path instanceof SplFileInfo) {
            $path = $path->getPathname();
        }

        Assert::string($path, '$fileInfo must be a string to a path or an instance of \SplFileInfo');

        return new static($path);
    }

    public function getContents(bool $lock = false): string
    {
        if ($this->isDir() === true) {
            return '';
        }

        return Filesystem::get($this->getPathname(), $lock);
    }

    public function isHidden(): bool
    {
        return Filesystem::isHidden($this->getPathname());
    }

    public function getShort(string $parentDirectory): ?string
    {
        return Filesystem::getShort($this->getPathname(), $parentDirectory);
    }

    public function hasExtension(string $ext, bool $caseSensitive = true): bool
    {
        return Filesystem::hasExtension($this->getPathname(), $ext, $caseSensitive);
    }

    public function assertReadable(): self
    {
        Assert::readable($this->getPathname());

        return $this;
    }

    public function assertWritable(): self
    {
        Assert::writeable($this->getPathname());

        return $this;
    }

    public function assertDirectory(): self
    {
        Assert::notFile($this->getPathname());
        Assert::directory($this->getPathname());

        return $this;
    }

    public function assertFile(): self
    {
        Assert::notDirectory($this->getPathname());
        Assert::file($this->getPathname());

        return $this;
    }
}