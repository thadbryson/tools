<?php

declare(strict_types = 1);

namespace Tests\Unit\Validation;

use Tests\Support\Stubs\UserStub;
use Tool\Validation\Validator;

/**
 * Test \Tool\Validator\Validator Class
 */
class ValidatorTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    private $data = [
        'type'      => UserStub::class,
        'method'    => 'myMethod',
        'static'    => 'myStaticMethod',
        'id'        => 1,
        'name'      => 'Test',
        'is_active' => true,
        'friends'   => ['Tom', 'Jerry', 'Ren', 'Stimpy'],
    ];

    private $rules = [
        'type'      => 'required|class_exists',
        'method'    => 'required|method_exists:' . UserStub::class,
        'static'    => 'required|method_exists:' . UserStub::class,
        'id'        => 'required|integer|min:1|max:10',
        'name'      => 'string',
        'code'      => 'string',
        'is_active' => 'required|boolean',
        'friends.*' => 'string|min:3',
    ];

    private $messages = [
        'required' => ':attribute is always necessary.',
        'string'   => ':attribute must be a valid UTF-8 string.',
    ];

    private $customAttributes = [
        'is_active' => 'Active',
        'type'      => 'User Class',
    ];

    public function _before(): void
    {
        // Reset to Factory defaults.
        Validator::setFactory();
    }

    public function testSingle(): void
    {
        $result = Validator::single(null, 'required|integer|min:5', $this->messages);

        $this->tester->testValidationResult($result, [
            'data' => ['Data is always necessary.'],
        ]);

        $result = Validator::single(10, 'required|integer|min:5:max10', $this->messages);

        $this->tester->testValidationSuccess($result);
    }

    public function testValidate(): void
    {
        $result = Validator::validate($this->data, $this->rules, $this->messages, $this->customAttributes);

        $this->tester->testValidationSuccess($result);

        $result = Validator::validate($this->data, []);

        $this->tester->testValidationSuccess($result);

        // $messages for "string" and "required" will show.
        $this->rules['id'] = 'string';
        unset($this->data['is_active']);

        $result = Validator::validate($this->data, $this->rules, $this->messages, $this->customAttributes);

        $this->tester->testValidationResult($result, [
            'id'        => ['id must be a valid UTF-8 string.'],
            'is_active' => ['Active is always necessary.'],
        ]);
    }
}
