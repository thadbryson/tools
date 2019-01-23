<?php

declare(strict_types = 1);

namespace Tests\Unit\Functions;

use function tool\functions\reflect\reflect_class;
use function tool\functions\reflect\reflect_class_deep;

class ReflectFunctionsTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    function testReflectClass(): void
    {
        $this->tester->expectException(new \InvalidArgumentException('Expected an existing class name. Got: ""'),
            function () {

                reflect_class('');
            });

        $this->assertEquals([
            'reflection' => new \ReflectionClass(\DateTime::class),
            'class'      => 'DateTime',
            'namespace'  => '',
            'short_name' => 'DateTime',
            'extension'  => 'date',
            'file'       => false,
            'interfaces' => [
                \DateTimeInterface::class
            ],
            'extends'    => false,
            'constants'  => [
                'ATOM'             => 'Y-m-d\TH:i:sP',
                'COOKIE'           => 'l, d-M-Y H:i:s T',
                'ISO8601'          => 'Y-m-d\TH:i:sO',
                'RFC822'           => 'D, d M y H:i:s O',
                'RFC850'           => 'l, d-M-y H:i:s T',
                'RFC1036'          => 'D, d M y H:i:s O',
                'RFC1123'          => 'D, d M Y H:i:s O',
                'RFC7231'          => 'D, d M Y H:i:s \G\M\T',
                'RFC2822'          => 'D, d M Y H:i:s O',
                'RFC3339'          => 'Y-m-d\TH:i:sP',
                'RFC3339_EXTENDED' => 'Y-m-d\TH:i:s.vP',
                'RSS'              => 'D, d M Y H:i:s O',
                'W3C'              => 'Y-m-d\TH:i:sP'
            ],
            'properties' => [],
            'methods'    => [
                '__construct',
                '__wakeup',
                '__set_state',
                'createFromImmutable',
                'createFromFormat',
                'getLastErrors',
                'format',
                'modify',
                'add',
                'sub',
                'getTimezone',
                'setTimezone',
                'getOffset',
                'setTime',
                'setDate',
                'setISODate',
                'setTimestamp',
                'getTimestamp',
                'diff'
            ]
        ], reflect_class(\DateTime::class));
    }

    function testReflectClassDeep(): void
    {
        $this->tester->expectException(new \InvalidArgumentException('Expected an existing class name. Got: ""'),
            function () {

                reflect_class_deep('');
            });

        $this->assertEquals([
            'reflection' => new \ReflectionClass(\DateInterval::class),
            'class'      => 'DateInterval',
            'namespace'  => '',
            'short_name' => 'DateInterval',
            'extension'  => 'date',
            'file'       => false,
            'interfaces' => [],
            'extends'    => false,
            'constants'  => [],
            'properties' => [
                'y'      => [],
                'm'      => [],
                'd'      => [],
                'h'      => [],
                'i'      => [],
                's'      => [],
                'f'      => [],
                'invert' => [],
                'days'   => [],
            ],
            'methods'    => [
                '__construct'          => [
                    'name'           => '__construct',
                    'parameters'     => [
                        'interval_spec' => [
                            'name'         => 'interval_spec',
                            'position'     => 0,
                            'type'         => null,
                            'is_nullable'  => false,
                            'is_array'     => false,
                            'is_callable'  => false,
                            'is_optional'  => false,
                            'is_reference' => false,
                            'has_default'  => false,
                            'default'      => null,
                        ]
                    ],
                    'return_type'    => null,
                    'is_static'      => false,
                    'is_public'      => true,
                    'is_private'     => false,
                    'is_protected'   => false,
                    'is_final'       => false,
                    'is_variadic'    => false,
                    'is_constructor' => true,
                    'is_destructor'  => false,
                ],
                'createFromDateString' => [
                    'name'           => 'createFromDateString',
                    'parameters'     => [
                        'time' => [
                            'name'         => 'time',
                            'position'     => 0,
                            'type'         => null,
                            'is_nullable'  => false,
                            'is_array'     => false,
                            'is_callable'  => false,
                            'is_optional'  => false,
                            'is_reference' => false,
                            'has_default'  => false,
                            'default'      => null
                        ]
                    ],
                    'return_type'    => null,
                    'is_static'      => true,
                    'is_public'      => true,
                    'is_private'     => false,
                    'is_protected'   => false,
                    'is_final'       => false,
                    'is_variadic'    => false,
                    'is_constructor' => false,
                    'is_destructor'  => false
                ],
                '__wakeup'             => [
                    'name'           => '__wakeup',
                    'parameters'     => [],
                    'return_type'    => null,
                    'is_static'      => false,
                    'is_public'      => true,
                    'is_private'     => false,
                    'is_protected'   => false,
                    'is_final'       => false,
                    'is_variadic'    => false,
                    'is_constructor' => false,
                    'is_destructor'  => false
                ],
                '__set_state'          => [
                    'name'           => '__set_state',
                    'parameters'     => [
                        'array' => [
                            'name'         => 'array',
                            'position'     => 0,
                            'type'         => new \ReflectionNamedType,
                            'is_nullable'  => false,
                            'is_array'     => true,
                            'is_callable'  => false,
                            'is_optional'  => false,
                            'is_reference' => false,
                            'has_default'  => false,
                            'default'      => null
                        ]
                    ],
                    'return_type'    => null,
                    'is_static'      => true,
                    'is_public'      => true,
                    'is_private'     => false,
                    'is_protected'   => false,
                    'is_final'       => false,
                    'is_variadic'    => false,
                    'is_constructor' => false,
                    'is_destructor'  => false
                ],
                'format'               => [
                    'name'           => 'format',
                    'parameters'     => [
                        'format' => [
                            'name'         => 'format',
                            'position'     => 0,
                            'type'         => null,
                            'is_nullable'  => false,
                            'is_array'     => false,
                            'is_callable'  => false,
                            'is_optional'  => false,
                            'is_reference' => false,
                            'has_default'  => false,
                            'default'      => null
                        ]
                    ],
                    'return_type'    => null,
                    'is_static'      => false,
                    'is_public'      => true,
                    'is_private'     => false,
                    'is_protected'   => false,
                    'is_final'       => false,
                    'is_variadic'    => false,
                    'is_constructor' => false,
                    'is_destructor'  => false
                ],

            ]
        ], reflect_class_deep(\DateInterval::class));
    }

    function testReflectProperty(): void
    {

    }

    function testReflectMethod(): void
    {

    }
}



