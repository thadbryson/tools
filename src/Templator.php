<?php

declare(strict_types = 1);

namespace Tool\Support;

use Tool\Support\Validation\Assert;
use Twig_Environment;
use Twig_Loader_Array;
use function file_get_contents;

/**
 * Build strings or files with a templating engine (default is Twig).
 *
 */
class Templator
{
    /**
     * Global Template object - default is \Twig_Environment
     *
     * @var mixed
     */
    protected static $engine;

    /**
     * Get global Twig_Environment object.
     */
    public static function getEngine(): Twig_Environment
    {
        // Create and set Twig if it's not already.
        if (static::$engine === null) {
            $loader = new Twig_Loader_Array([]);

            static::setEngine(new Twig_Environment($loader));
        }

        return static::$engine;
    }

    /**
     * Set global templating engine object to be used
     */
    public static function setEngine($engine): void
    {
        static::$engine = $engine;
    }

    /**
     * Clear template engine object. Set it to null so the default object will be used.
     */
    public static function clearEngine(): void
    {
        static::$engine = null;
    }

    /**
     * Get compiled string.
     */
    public static function make(string $template, array $vars): string
    {
        return static::getEngine()
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

        return Assert::string($contents, sprintf('Could not read template file %s.', $templateFilepath));
    }
}
