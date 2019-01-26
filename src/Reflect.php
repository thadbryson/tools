<?php

declare(strict_types = 1);

namespace Tool;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use Tool\Validation\Assert;

/**
 * Class Reflect
 *
 * Get Reflection data on classes, properties, methods, and parameters of methods.
 */
class Reflect
{
    /**
     * Run ReflectionClass() on class with $className given and run Reflection on properties and methods.
     *
     * @throws \ReflectionException
     */
    public static function classDeep(string $className): array
    {
        $reflect = static::class($className);

        foreach ($reflect['properties'] as $name => $defaultValue) {
            $reflect['properties'][$name] = static::property($className, $name);
        }

        foreach ($reflect['methods'] as $key => $method) {
            unset($reflect['methods'][$key]);

            $reflect['methods'][$method] = static::method($className, $method);
        }

        return $reflect;
    }

    /**
     * Run ReflectionClass() on class with $className given.
     *
     * @throws \ReflectionException
     */
    public static function class(string $className): array
    {
        Assert::classExists($className);

        $refl = new ReflectionClass($className);

        return [
            'reflection' => $refl,
            'class'      => $className,
            'namespace'  => $refl->getNamespaceName(),
            'short_name' => $refl->getShortName(),
            'extension'  => $refl->getExtensionName(),
            'file'       => $refl->getFileName(),
            'interfaces' => $refl->getInterfaceNames(),
            'extends'    => $refl->getParentClass(),
            'constants'  => $refl->getConstants(),
            'properties' => $refl->getDefaultProperties(),
            'methods'    => Collection::make($refl->getMethods())
                ->map(function (ReflectionMethod $refl) {

                    return $refl->getName();
                })
                ->all(),
        ];
    }

    /**
     * Run ReflectionProperty() on a class property.
     *
     * @throws \ReflectionException
     */
    public static function property(string $className, string $propertyName): array
    {
        $refl = new ReflectionProperty($className, $propertyName);

        return [
            'name'         => $propertyName,
            'is_default'   => $refl->isDefault(),
            'is_static'    => $refl->isStatic(),
            'is_public'    => $refl->isPublic(),
            'is_private'   => $refl->isPrivate(),
            'is_protected' => $refl->isProtected(),
        ];
    }

    /**
     * Run ReflectionMethod() on a class method.
     *
     * @throws \ReflectionException
     */
    public static function method(string $className, string $methodName): array
    {
        $refl = new ReflectionMethod($className, $methodName);

        $parameters = [];

        foreach ($refl->getParameters() as $reflParam) {
            $hasDefault = $reflParam->isDefaultValueAvailable();

            $name = $reflParam->getName();

            $parameters[$name] = [
                'name'         => $name,
                'position'     => $reflParam->getPosition(),
                'type'         => $reflParam->getType(),
                'is_nullable'  => $reflParam->allowsNull(),
                'is_array'     => $reflParam->isArray(),
                'is_callable'  => $reflParam->isCallable(),
                'is_optional'  => $reflParam->isOptional(),
                'is_reference' => $reflParam->isPassedByReference(),
                'has_default'  => $hasDefault,
                'default'      => $hasDefault ? $reflParam->getDefaultValue() : null,
            ];
        }

        return [
            'name'           => $methodName,
            'parameters'     => $parameters,
            'return_type'    => $refl->getReturnType(),
            'is_static'      => $refl->isStatic(),
            'is_public'      => $refl->isPublic(),
            'is_private'     => $refl->isPrivate(),
            'is_protected'   => $refl->isProtected(),
            'is_final'       => $refl->isFinal(),
            'is_variadic'    => $refl->isVariadic(),
            'is_constructor' => $refl->isConstructor(),
            'is_destructor'  => $refl->isDestructor(),
        ];
    }
}
