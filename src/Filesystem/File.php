<?php

declare(strict_types = 1);

namespace Tool\Filesystem;

use SplFileInfo;

/**
 * Class File
 *
 * @method @static File make(string $path)
 */
class File extends Pathinfo
{
    public function __construct(string $path)
    {
        parent::__construct($path);

        $this->assertFile();
    }

    /**
     * @param string|SplFileInfo $path
     * @return File
     */
    public static function make($path)
    {
        $pathinfo = parent::make($path);

        return new static($pathinfo->getPathname());
    }

    public static function makeEnsure(string $path, string $contents = ''): self
    {
        Filesystem::ensureFile($path, $contents);

        return new static($path);
    }

    public function save(string $contents, bool $lock = false): int
    {
        return Filesystem::save($this->getPathname(), $contents, $lock);
    }

    public function copy(string $target): ?File
    {
        if (Filesystem::copy($this->getPathname(), $target) === true) {
            return new static($target);
        }

        return null;
    }

    public function move(string $target): ?File
    {
        if (Filesystem::move($this->getPathname(), $target) === true) {
            return new static($target);
        }

        return null;
    }

    public function delete(): bool
    {
        return Filesystem::delete($this->getPathname());
    }
}