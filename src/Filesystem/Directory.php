<?php

declare(strict_types = 1);

namespace Tool\Filesystem;

use SplFileInfo;

class Directory extends Pathinfo
{
    public function __construct(string $path)
    {
        parent::__construct($path);

        $this->assertDirectory();
    }

    /**
     * @param string|SplFileInfo $path
     * @return Directory
     */
    public static function make($path)
    {
        $pathinfo = parent::make($path);

        return new static($pathinfo->getPathname());
    }

    public static function makeEnsure(string $path, int $mode = null): self
    {
        Filesystem::ensureDirectory($path, $mode);

        return new static($path);
    }

    /**
     * @param string   $target
     * @param int|null $options
     * @return Directory|null
     * @codeCoverageIgnore
     */
    public function copy(string $target, int $options = null): ?Directory
    {
        if (Filesystem::copyDirectory($this->getPathname(), $target, $options) === true) {
            return new static($target);
        }

        return null;
    }

    /**
     * @param string $target
     * @return Directory|null
     * @codeCoverageIgnore
     */
    public function move(string $target): ?Directory
    {
        if (Filesystem::move($this->getPathname(), $target) === true) {
            return new static($target);
        }

        return null;
    }

    public function delete(): bool
    {
        return Filesystem::deleteDirectory($this->getPathname());
    }

    public function deleteChildren(): bool
    {
        return Filesystem::deleteDirectory($this->getPathname(), true);
    }
}