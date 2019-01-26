<?php

declare(strict_types = 1);

namespace Tests\Unit;

use Tests\Support\Stubs\ReflectionStub;
use function realpath;
use Tool\Reflect;

class ReflectTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    function testReflectClass(): void
    {
        $this->tester->expectThrowable(\InvalidArgumentException::class,
            function () {
                Reflect::class('');
            });

        $this->assertEquals([
            'reflection' => new \ReflectionClass(\DateTime::class),
            'class'      => 'DateTime',
            'namespace'  => '',
            'short_name' => 'DateTime',
            'extension'  => 'date',
            'file'       => false,
            'interfaces' => [
                \DateTimeInterface::class,
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
                'W3C'              => 'Y-m-d\TH:i:sP',
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
                'diff',
            ],
        ], Reflect::class(\DateTime::class));
    }

    public function testReflectClassDeep(): void
    {
        $this->tester->expectThrowable(\InvalidArgumentException::class,
            function () {
                Reflect::classDeep('');
            });

        $this->assertEquals([
            'reflection' => new \ReflectionClass(ReflectionStub::class),
            'class'      => ReflectionStub::class,
            'namespace'  => 'Tests\Support\Stubs',
            'short_name' => 'ReflectionStub',
            'extension'  => false,
            'file'       => realpath(__DIR__ . '/../_support/Stubs/ReflectionStub.php'),
            'interfaces' => [],
            'extends'    => false,
            'constants'  => [],
            'properties' => [
                'prop' => [
                    'name'         => 'prop',
                    'is_default'   => true,
                    'is_static'    => false,
                    'is_public'    => true,
                    'is_private'   => false,
                    'is_protected' => false,
                ],
            ],
            'methods'    => [
                'method' => [
                    'name'           => 'method',
                    'parameters'     => [
                        'arg' => [
                            'name'         => 'arg',
                            'position'     => 0,
                            'type'         => new \ReflectionNamedType,
                            'is_nullable'  => false,
                            'is_array'     => false,
                            'is_callable'  => false,
                            'is_optional'  => false,
                            'is_reference' => false,
                            'has_default'  => false,
                            'default'      => null,
                        ],
                    ],
                    'return_type'    => new \ReflectionNamedType,
                    'is_static'      => false,
                    'is_public'      => true,
                    'is_private'     => false,
                    'is_protected'   => false,
                    'is_final'       => false,
                    'is_variadic'    => false,
                    'is_constructor' => false,
                    'is_destructor'  => false,
                ],
            ],
        ], Reflect::classDeep(ReflectionStub::class));
    }

    public function testReflectProperty(): void
    {
        $this->assertEquals([
            'name'         => 'prop',
            'is_default'   => true,
            'is_static'    => false,
            'is_public'    => true,
            'is_private'   => false,
            'is_protected' => false,
        ], Reflect::property(ReflectionStub::class, 'prop'));
    }

    public function testReflectMethod(): void
    {
        $this->assertEquals([
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
                    'default'      => null,
                ],
            ],
            'return_type'    => null,
            'is_static'      => false,
            'is_public'      => true,
            'is_private'     => false,
            'is_protected'   => false,
            'is_final'       => false,
            'is_variadic'    => false,
            'is_constructor' => false,
            'is_destructor'  => false,
        ], Reflect::method(\DateInterval::class, 'format'));
    }
}
