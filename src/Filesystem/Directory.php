<?php

declare(strict_types = 1);

namespace Tool\Filesystem;

class Directory extends Pathinfo
{
    public function __construct(string $path)
    {
        parent::__construct($path);

        $this->assertDirectory();
    }

    public static function makeEnsure(string $path, int $mode = null, bool $recursive = false, bool $force = false)
    {
        Filesystem::ensureDirectory($path, $mode, $recursive, $force);

        return new static($path);
    }

    public function copy(string $target, int $options = null): ?Directory
    {
        if (Filesystem::copyDirectory($this->getPathname(), $target, $options) === true) {
            return new static($target);
        }

        return null;
    }

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