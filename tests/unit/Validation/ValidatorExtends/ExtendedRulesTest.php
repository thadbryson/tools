<?php

declare(strict_types = 1);

namespace Tests\Unit\Validation\ValidatorExtends;

use Tests\Support\Stubs\UserStub;
use Tool\Validation\Validator;

class ExtendedRulesTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    private $data = [
        'type'   => UserStub::class,
        'method' => 'myMethod',
        'static' => 'myStaticMethod',
    ];

    private $rules = [
        'type'   => 'required|class_exists',
        'method' => 'required|method_exists:' . UserStub::class,
        'static' => 'required|method_exists:' . UserStub::class
    ];

    public function testExtendedRules(): void
    {
        $result = Validator::validate($this->data, $this->rules);

        $this->assertTrue(class_exists(UserStub::class));
        $this->assertTrue(method_exists(UserStub::class, 'myMethod'));
        $this->assertTrue(method_exists(UserStub::class, 'myStaticMethod'));

        $this->tester->testValidationResult($result, []);

        // Test a failure.
        $result = Validator::validate([
            'type'   => UserStub::class . 'Nope',
            'method' => '_myMethod',
            'static' => '_myStaticMethod'
        ], [
            'type'   => 'required|class_exists',
            'method' => 'required|method_exists:' . UserStub::class,
            'static' => 'required|method_exists:' . UserStub::class
        ]);

        $this->assertFalse(class_exists(UserStub::class . 'Nope'));
        $this->assertFalse(method_exists(UserStub::class, '_myMethod'));
        $this->assertFalse(method_exists(UserStub::class, '_myStaticMethod'));

        $this->tester->testValidationResult($result, [
            'type'   => ['The type class does not exist.'],
            'method' => ['The method method does not exist on this class.'],
            'static' => ['The static method does not exist on this class.']
        ]);
    }

    public function testObjectRules(): void
    {
        $result = Validator::validate(
            ['datetime' => new \DateTime],
            ['datetime' => 'object:' . \DateTime::class]
        );

        $this->tester->testValidationResult($result, []);

        $result = Validator::validate(
            ['datetime' => new \ReflectionClass(\DateTime::class)],
            ['datetime' => 'object:' . \DateTime::class]);

        $this->tester->testValidationResult($result, [
            'datetime' => ['The datetime is not a valid object.']
        ]);
    }
}
