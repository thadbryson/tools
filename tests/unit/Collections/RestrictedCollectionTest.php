<?php

declare(strict_types = 1);

namespace Tests\Unit\Collections;

use Tests\Support\Stubs\RestrictedCollectionStub;
use Tool\Exceptions\Error;
use Tool\Support\Collection;
use Tool\Support\Collections\RestrictedCollection;

/**
 * Class RestrictedCollection
 */
class RestrictedCollectionTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    /**
     * @var RestrictedCollection
     */
    private $coll;

    private $rules = [
        'id'   => 'required|integer',
        'what' => 'required|string'
    ];

    private $rulesEach = [
        '*.id'   => 'required|integer',
        '*.what' => 'required|string'
    ];

    private $messages = [
        'id'   => [
            'required' => ':attiribute is needed here.'
        ],
        'what' => 'required|string'
    ];

    private $customAttributes = [
        'id' => 'ID attr',
    ];

    public function _before(): void
    {
        $this->coll = new RestrictedCollection([
            'id'   => 10,
            'what' => 'me'
        ]);

        $this->coll->setRules($this->rules);
    }

    public function testConstructorAssert(): void
    {
        $this->tester->expectException(Error::class, function () {

            new RestrictedCollectionStub([
                'id'   => 10,
                'what' => '??'
            ]);
        });
    }

    public function testMakeType(): void
    {
        $integers = RestrictedCollection::makeType('boolean', []);

        $integers->append(true);
        $integers->append(false);

        $this->tester->expectException(Error::class, function () {

            RestrictedCollection::makeType('integer', [1, 2, 3, false]);
        });
    }

    public function testSetRulesMessagesCustomAttributes(): void
    {
        $this->tester->assertArr([], $this->coll->getMessages());
        $this->tester->assertArr([], $this->coll->getCustomAttributes());

        $this->coll
            ->setMessages($this->messages)
            ->setCustomAttributes($this->customAttributes);

        $this->tester->assertArr($this->rules, $this->coll->getRules());
        $this->tester->assertArr($this->messages, $this->coll->getMessages());
        $this->tester->assertArr($this->customAttributes, $this->coll->getCustomAttributes());
    }

    public function testSetRulesEach(): void
    {
        $this->coll->setRulesToEach();

        $this->tester->assertArr($this->rulesEach, $this->coll->getRules());
    }

    public function testAssert(): void
    {
        $this->tester->expectException(Error::class, function () {

            $this->coll->setRules(['id' => 'string'])
                       ->assert();
        });
    }

    public function testTransform(): void
    {
        $this->coll->setRulesToEach()
                   ->transform(function () {

                       return ['id' => 4, 'what' => '??????'];
                   });

        $this->tester->assertRestrictedCollection([
            'id'   => ['id' => 4, 'what' => '??????'],
            'what' => ['id' => 4, 'what' => '??????']
        ], $this->coll);
    }

    public function testPrepend(): void
    {
        $this->coll
            ->setRulesToEach()
            ->reset([
                ['id' => 2, 'what' => 'a']
            ])
            ->prepend(['id' => 1, 'what' => 'something']);

        $this->tester->assertRestrictedCollection([
            ['id' => 1, 'what' => 'something'],
            ['id' => 2, 'what' => 'a']
        ], $this->coll);
    }

    public function testOffsetSet(): void
    {
        $this->coll[] = ['id' => 3, 'what' => 'B'];

        $this->tester->assertRestrictedCollection([
            'id'   => 10,
            'what' => 'me',
            ['id' => 3, 'what' => 'B']
        ], $this->coll);
    }
}
