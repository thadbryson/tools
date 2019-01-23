<?php

declare(strict_types = 1);

namespace Tool\Support;

use Tool\Validation\Assert;
use Twig_Environment;
use Twig_Loader_Array;
use function file_get_contents;
use function file_put_contents;

/**
 * Build strings or files with Twig template strings/files.
 *
 */
class Templator
{
    /**
     * Global Twig_Environment object.
     *
     * @var Twig_Environment
     */
    protected static $twig;

    /**
     * Get global Twig_Environment object.
     */
    public static function getTwig(): Twig_Environment
    {
        // Create and set Twig if it's not already.
        if (static::$twig === null) {
            $loader = new Twig_Loader_Array([]);

            static::setTwig(new Twig_Environment($loader));
        }

        return static::$twig;
    }

    /**
     * Set global Twig_Environment object to be used
     */
    public static function setTwig(Twig_Environment $twig): void
    {
        static::$twig = $twig;
    }

    /**
     * Clear Twig_Environment object. Set it to null so the default object will be used.
     */
    public static function clearTwig(): void
    {
        static::$twig = null;
    }

    /**
     * Get compiled string.
     */
    public static function make(string $template, array $vars): string
    {
        return static::getTwig()
                     ->createTemplate($template)
                     ->render($vars);
    }

    /**
     * Get compiled string from a template file.
     */
    public static function fromFile(string $templateFilepath, array $vars): string
    {
        $contents = static::readTemplate($templateFilepath);

        // Now return from compiled string.
        return static::make($contents, $vars);
    }

    protected static function readTemplate(string $templateFilepath): string
    {
        $contents = file_get_contents($templateFilepath);

        Assert::string($contents, sprintf('Could not read template file %s.', $templateFilepath));

        return $contents;
    }
}
