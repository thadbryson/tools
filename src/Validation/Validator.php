<?php

declare(strict_types = 1);

namespace Tool\Validation;

use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Tool\Arr;
use function is_object;

/**
 * Validator Class
 */
class Validator
{
    /**
     * @var Factory
     */
    private static $factory;

    // Disable instances. Only create statically.

    /** @codeCoverageIgnore */
    private function __construct()
    {
    }

    public static function single($data, string $rules, array $messages = [], string $attributeName = 'Data'): Result
    {
        $data            = ['data' => $data];
        $customAttribute = ['data' => $attributeName];

        return static::validate($data, Arr::dot(['data' => $rules]), $messages, $customAttribute);
    }

    public static function validate(array $data, array $rules, array $messages = [],
        array $customAttributes = []): Result
    {
        if ($rules === []) {
            return Result::success();
        }

        $validator = static::getFactory()->make($data, $rules, $messages, $customAttributes);

        // Run validation.
        $validator->passes();

        return new Result($validator->messages());
    }

    private static function getFactory(): Factory
    {
        if (static::$factory === null) {
            static::setFactory();
        }

        return static::$factory;
    }

    public static function setFactory(string $directory = __DIR__ . '/../../lang', string $language = 'en'): void
    {
        $directory = rtrim($directory, '/');

        Assert::directory($directory, '%s is not a directory.');
        Assert::directory($directory . '/' . $language, 'Language directory %s is not a directory.');

        Assert::readable($directory, 'Translator directory %s is not readable.');
        Assert::readable($directory . '/' . $language, 'Validator Translator language directory %s is not readable.');

        $loader     = new FileLoader(new Filesystem, $directory);
        $translator = new Translator($loader, $language);

        $factory = new Factory($translator, new Container);

        static::$factory = static::attachRules($factory);
    }

    private static function attachRules(Factory $factory): Factory
    {
        $factory->extend('class_exists', function (string $_, string $class): bool {

            return class_exists($class);
        });

        $factory->extend('method_exists', function (string $_, string $method, array $parameters): bool {

            $class = $parameters[0] ?? null;

            return method_exists($class, $method);
        });

        $factory->extend('object', function (string $_, $object, array $parameters): bool {

            $class = $parameters[0] ?? null;

            return is_object($object) && $object instanceof $class;
        });

        return $factory;
    }
}
