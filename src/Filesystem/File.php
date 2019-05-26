<?php

declare(strict_types = 1);

namespace Tool\Filesystem;

use SplFileInfo;
use function fclose;
use function is_resource;

/**
 * Class File
 *
 * @method @static File make(string $path)
 */
class File extends Pathinfo
{
    /**
     * @var resource|null
     */
    private $fopen;

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

    /**
     * Closes any open file handles.
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * @param string $permissions
     * @return false|resource
     * @codeCoverageIgnore
     */
    public function open(string $permissions = 'r')
    {
        if (is_resource($this->fopen) === false) {

            $this->fopen = fopen($this->getPathname(), $permissions);
        }

        return $this->fopen;
    }

    /**
     * @return $this
     * @codeCoverageIgnore
     */
    public function close()
    {
        if (is_resource($this->fopen) === true) {
            fclose($this->fopen);
        }

        return $this;
    }

    public function save(string $contents, bool $lock = false): int
    {
        return Filesystem::save($this->getPathname(), $contents, $lock);
    }

    /**
     * @param string $target
     * @return File|null
     * @codeCoverageIgnore
     */
    public function copy(string $target): ?File
    {
        if (Filesystem::copy($this->getPathname(), $target) === true) {
            return new static($target);
        }

        return null;
    }

    /**
     * @param string $target
     * @return File|null
     * @codeCoverageIgnore
     */
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