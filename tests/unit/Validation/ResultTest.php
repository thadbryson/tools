<?php

declare(strict_types = 1);

namespace Tests\Unit\Validation;

use Illuminate\Support\MessageBag;
use Tool\Validation\Exceptions\ValidationException;
use Tool\Validation\Result;

/**
 * Result Class
 */
class ResultTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var Result
     */
    private $success;

    /**
     * @var array
     */
    private $failureMessages;

    /**
     * @var Result
     */
    private $failure;

    public function _before(): void
    {
        $this->success = new Result(new MessageBag);

        $this->failureMessages = [
            'id'    => ['id is necessary'],
            'name'  => ['name must be a string with at least 25 characters'],
            'other' => ['other cannot be true'],
        ];

        $this->failure = new Result(new MessageBag($this->failureMessages));
    }

    public function testStaticFromArray(): void
    {
        $success = Result::fromArray([]);
        $failure = Result::fromArray(['some' => ['whatever']]);

        $this->tester->testValidationResult($success, []);
        $this->tester->testValidationResult($failure, ['some' => ['whatever']]);
    }

    public function testStaticSuccess(): void
    {
        $success = Result::success();

        $this->tester->testValidationResult($success, []);
    }

    public function testAssert(): void
    {
        $this->success->assert();

        $this->tester->expectThrowable(ValidationException::class, function () {
            $this->failure->assert('Message here.');
        });
    }

    public function testThrow(): void
    {
        $this->tester->expectThrowable(ValidationException::class, function () {
            $this->success->throw('message success - nope');
        });

        $this->tester->expectThrowable(ValidationException::class, function () {
            $this->failure->throw('err');
        });
    }

    public function testGetErrorsFlat(): void
    {
        $this->assertEquals([], $this->success->getErrorsFlat());
        $this->assertEquals([
            'id is necessary',
            'name must be a string with at least 25 characters',
            'other cannot be true',
        ], $this->failure->getErrorsFlat());
    }

    public function testGetMessageBag(): void
    {
        $this->assertEquals([], $this->success->getMessageBag()->messages());
        $this->assertEquals($this->failureMessages, $this->failure->getMessageBag()->messages());
    }

    public function testIsSuccess(): void
    {
        $this->tester->testValidationResult($this->success, []);

        $this->assertTrue($this->success->isSuccess());
        $this->assertFalse($this->failure->isSuccess());
    }

    public function testIsFailure(): void
    {
        $this->tester->testValidationResult($this->failure, $this->failureMessages);

        $this->assertFalse($this->success->isFailure());
        $this->assertTrue($this->failure->isFailure());
    }
}
