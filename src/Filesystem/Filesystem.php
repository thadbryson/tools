<?php

declare(strict_types = 1);

namespace Tool\Filesystem;

use function array_slice;
use Tool\Str;
use Tool\Validation\Assert;
use function basename;
use function implode;
use function substr;
use const DIRECTORY_SEPARATOR;

/**
 * Class Filesystem
 *
 * @mixin \Illuminate\Filesystem\Filesystem
 */
class Filesystem
{
    public const DEFAULT_FILE_PERMS = 0644;

    public const DEFAULT_DIR_PERMS = 0655;

    public static function __callStatic(string $method, array $arguments)
    {
        $filesystem = new \Illuminate\Filesystem\Filesystem;

        Assert::methodExists($method, $filesystem, sprintf('%s does not have method: %s', static::class, $method));

        return $filesystem->{$method}(...$arguments);
    }

    public static function setPermissions(string $path, int $mode = null): ?bool
    {
        if (static::exists($path) === false) {
            return null;
        }

        $default = static::isDirectory($path) ?
            static::DEFAULT_DIR_PERMS :
            static::DEFAULT_FILE_PERMS;

        return static::chmod($path, $mode ?? $default);
    }

    public static function getPermissionsDescription(string $path): ?string
    {
        if (static::exists($path) === false) {
            return null;
        }

        $result = static::chmod($path);

        if ($result === false) {
            return null;
        }

        return $result;
    }

    /**
     * @alias of put()
     */
    public static function save(string $path, string $contents, bool $lock = false): int
    {
        return static::put($path, $contents, $lock);
    }

    public static function ensureFile(string $path, string $contents = '', bool $lock = false): bool
    {
        if (static::isFile($path) === true) {
            return false;
        }

        static::save($path, $contents, $lock);

        return static::isFile($path);
    }

    public static function ensureDirectory(string $path, int $mode = null): bool
    {
        if (static::isDirectory($path) === true) {
            return false;
        }

        return static::makeDirectory($path, $mode ?? static::DEFAULT_DIR_PERMS, true, true);
    }

    public static function isHidden(string $path): bool
    {
        $basename = basename($path);

        return substr($basename, 0, 1) === '.';
    }

    public static function getShort(string $path, string $parentDirectory): ?string
    {
        $path            = explode(DIRECTORY_SEPARATOR, trim($path, DIRECTORY_SEPARATOR));
        $parentDirectory = explode(DIRECTORY_SEPARATOR, trim($parentDirectory, DIRECTORY_SEPARATOR));

        if ($parentDirectory !== array_slice($path, 0, count($parentDirectory))) {
            return null;
        }

        return Str::implode(DIRECTORY_SEPARATOR, $path)
            ->removeLeft(implode(DIRECTORY_SEPARATOR, $parentDirectory))
            ->trim(DIRECTORY_SEPARATOR)
            ->get();
    }

    public static function hasExtension(string $path, string $ext, bool $caseSensitive = true): bool
    {
        $infoExt = static::extension($path);

        if ($caseSensitive === false) {
            $infoExt = strtolower($infoExt);
            $ext     = strtolower($ext);
        }

        return ltrim($infoExt, '.') === ltrim($ext, '.');
    }
}
