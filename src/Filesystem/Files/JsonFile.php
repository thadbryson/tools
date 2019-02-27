<?php

declare(strict_types = 1);

namespace Tool\Filesystem\Files;

use Tool\Filesystem\File;
use function json_decode;

/**
 * Class JsonFile
 *
 * File with JSON contents.
 */
class JsonFile extends File
{
    public static function make($path): JsonFile
    {
        return new static($path);
    }

    public function getContentsArray(int $options = 0, int $depth = 512, bool $lock = false): array
    {
        $contents = parent::getContents($lock);

        return json_decode($contents, true, $depth, $options);
    }
}