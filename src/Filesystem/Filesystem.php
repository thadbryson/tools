<?php

declare(strict_types = 1);

namespace Tool\Filesystem;

use Tool\Str;
use Tool\Validation\Assert;
use function substr;
use const DIRECTORY_SEPARATOR;

class Filesystem extends \Illuminate\Filesystem\Filesystem
{
    public const DEFAULT_FILE_PERMS = 0644;

    public const DEFAULT_DIR_PERMS = 0655;

    public static function __callStatic(string $method, array $arguments)
    {
        $filesystem = new static;

        Assert::methodExists($method, $filesystem, 'Filesystem does not have method: ' . $method);

        return $filesystem->{$method}(...$arguments);
    }

    public function setPermissions(string $path, int $mode = null): bool
    {
        $default = static::isDirectory($path) ?
            static::DEFAULT_DIR_PERMS :
            static::DEFAULT_FILE_PERMS;

        return parent::chmod($path, $mode ?? $default);
    }

    public function getPermissionsDescription(string $path): ?string
    {
        $result = parent::chmod($path);

        if ($result === false) {
            return null;
        }

        return $result;
    }

    /**
     * @alias of put()
     */
    public function save(string $path, string $contents, bool $lock = false): int
    {
        return $this->put($path, $contents, $lock);
    }

    public function ensureFile(string $path, string $contents = '', bool $lock = false): bool
    {
        if ($this->isFile($path) === false) {
            return $this->save($path, $contents, $lock) > 0;
        }

        return false;
    }

    public function ensureDirectory(string $path, int $mode = null, bool $recursive = false, bool $force = false): bool
    {
        if ($this->isDirectory($path) === false) {
            return $this->makeDirectory($path, $mode ?? static::DEFAULT_DIR_PERMS, $recursive, $force);
        }

        return false;
    }

    public function isHidden(string $path): bool
    {
        return substr($path, 0, 1) === '.';
    }

    public function getShort(string $path, string $parentDirectory): string
    {
        $parentDirectory = trim($parentDirectory, DIRECTORY_SEPARATOR);

        return (string) Str::make($path)
            ->trim(DIRECTORY_SEPARATOR)
            ->removeLeft($parentDirectory)
            ->trim(DIRECTORY_SEPARATOR);
    }

    public function hasExtension(string $path, string $ext, bool $caseSensitive = true): bool
    {
        $infoExt = static::extension($path);

        if ($caseSensitive === true) {
            $infoExt = strtolower($infoExt);
            $ext     = strtolower($ext);
        }

        return ltrim($infoExt, '.') === ltrim($ext, '.');
    }
}
