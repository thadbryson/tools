<?php

declare(strict_types = 1);

namespace tool\support;

use ReflectionClass;

/**
 * Run ReflectionClass() on class with $className given.
 *
 * @param string $className
 *
 * @return array
 */
function reflect_class(string $className): array
{
    if (class_exists($className) === false) {
        return [];
    }

    $refl = new \ReflectionClass($className);

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
        'properties' => array_keys($refl->getProperties()),
        'methods'    => array_keys($refl->getMethods()),
    ];
}

/**
 * Run ReflectionClass() on class with $className given and run Reflection on properties and methods.
 *
 * @param string $className
 *
 * @return array
 */
function reflect_class_deep(string $className): array
{
    $reflect = reflect_class($className);

    // Check for class not found return.
    if ($reflect === []) {
        return [];
    }

    $reflect['properties'] = [];
    $reflect['methods']    = [];

    /** @var ReflectionClass $refl */
    $refl = $reflect['reflection'];

    foreach ($refl->getProperties() as $property) {
        $reflect['properties'][$property->getName()] = reflect_property($className, $property->getName());
    }

    foreach ($refl->getMethods() as $method) {
        $reflect['methods'][$method->getName()] = reflect_method($className, $method->getName());
    }

    return $reflect;
}

/**
 * Run ReflectionProperty() on a class property.
 *
 * @param string $className
 * @param string $propertyName
 *
 * @return array
 */
function reflect_property(string $className, string $propertyName): array
{
    $refl = new \ReflectionProperty($className, $propertyName);

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
 * @param string $className
 * @param string $methodName
 *
 * @return array
 */
function reflect_method(string $className, string $methodName): array
{
    $refl       = new \ReflectionMethod($className, $methodName);
    $parameters = [];

    foreach ($refl->getParameters() as $reflParam) {
        $hasDefault = $reflParam->isDefaultValueAvailable();

        $parameters[$reflParam->getName()] = [
            'name'         => $reflParam->getName(),
            'position'     => $reflParam->getPosition(),
            'type'         => $reflParam->getType(),
            'is_nullable'  => $reflParam->allowsNull(),
            'is_array'     => $reflParam->isArray(),
            'is_callable'  => $reflParam->isCallable(),
            'is_optional'  => $reflParam->isOptional(),
            'is_reference' => $reflParam->isPassedByReference(),
            'has_default'  => $hasDefault,
            'default'      => (($hasDefault) ? $reflParam->getDefaultValue() : null),
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
