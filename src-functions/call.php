<?php

declare(strict_types = 1);

namespace tool\support;

function call_method(object $object, string $method, array $args)
{
    check_param_type($object, ['object', 'string'], 'call_prepare_args', 'object');

    $args = call_prepare_args($args, $object, $method);

    // static method.
    return $object->method(...$args);
}

function call_static(string $class, string $method, array $args)
{
    check_class($class);
    check_method($class, $method);

    $args = call_prepare_args($args, $class, $method);

    return $class::$method(...$args);
}

/**
 * Get array of a method's parameter name's (key) => value passed in.
 *
 * @param array         $args
 * @param object|string $object
 * @param string        $method
 *
 * @return array
 */
function call_prepare_args(array $args, $object, string $method): array
{
    check_param_type($object, ['object', 'string'], 'call_prepare_args', 'object');
    check_method($object, $method);

    // Set the parameter order.
    $parameters = (new \ReflectionMethod($object, $method))->getParameters();
    $order      = [];

    foreach ($parameters as $parameter) {
        $name = $parameter->name;

        // Parameter not passed in?
        if (array_key_exists($name, $args) === false) {
            // - AND no default? throw 404
            if ($parameter->isDefaultValueAvailable() === true) {
                $args[$name] = $parameter->getDefaultValue();
            }
        }
        else {
            // Only set the order if it's defined in the arguments.
            // Otherwise it'll set the index of the location as the value.
            $order[] = $name;
        }
    }

    // Re-order for method call.
    return array_values(Arr::keyOrder($args, ...$order));
}
