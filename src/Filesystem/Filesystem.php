<?php

declare(strict_types = 1);

namespace Tool\Filesystem;

use Tool\Str;
use Tool\Validation\Assert;
use function array_slice;
use function basename;
use function implode;
use function json_decode;
use function substr;
use const DIRECTORY_SEPARATOR;

/**
 * Class Filesystem
 *
 * @method static bool exists(string $path)
 * @method static string get(string $path, bool $lock = false)
 * @method static string sharedGet(string $path)
 * @method static mixed getRequire(string $path)
 * @method static mixed requireOnce($file)
 * @method static string hash(string $path)
 * @method static int put(string $path, $contents, bool $lock = false)
 * @method static void replace(string $path, string $content)
 * @method static int prepend(string $path, string $data)
 * @method static int append(string $path, $data)
 * @method static bool|string chmod(string $path, int $mode = null)
 * @method static bool delete(string|string[] $paths)
 * @method static bool move(string $path, string $target)
 * @method static bool copy(string $path, string $target)
 * @method static void link($target, string $link)
 * @method static string name(string $path)
 * @method static string basename(string $path)
 * @method static string dirname(string $path)
 * @method static string extension(string $path)
 * @method static string type(string $path)
 * @method static string|false mimeType(string $path)
 * @method static int size(string $path)
 * @method static int lastModified(string $path)
 * @method static bool isDirectory(string $directory)
 * @method static bool isReadable(string $path)
 * @method static bool isWritable(string $path)
 * @method static bool isFile(string $file)
 * @method static array glob(string $pattern, int $flags = 0)
 * @method static \Symfony\Component\Finder\SplFileInfo[] files(string $directory, bool $hidden = false)
 * @method static \Symfony\Component\Finder\SplFileInfo[] allFiles(string $directory, bool $hidden = false)
 * @method static array directories(string $directory)
 * @method static bool makeDirectory(string $path, $mode = 0755, bool $recursive = false, bool $force = false)
 * @method static bool moveDirectory(string $from, string $to, bool $overwrite = false)
 * @method static bool copyDirectory(string $directory, $destination, int $options = null)
 * @method static bool deleteDirectory(string $directory, bool $preserve = false)
 * @method static bool deleteDirectories(string $directory)
 * @method static bool cleanDirectory(string $directory)
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

    public static function saveJson(string $path, $data, bool $lock = false): int
    {
        $json = json_encode($data, JSON_PRETTY_PRINT) . "\n";

        return static::save($path, $json, $lock);
    }

    public static function getJson(string $path, int $options = 0, int $depth = 512, bool $lock = false): array
    {
        $contents = static::get($path, $lock);

        return json_decode($contents, true, $depth, $options);
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
