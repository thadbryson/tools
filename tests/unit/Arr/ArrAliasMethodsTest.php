<?php

declare(strict_types = 1);

namespace Tests\Unit\Arr;

use Tool\Support\Arr;

class ArrAliasMethodsTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;


    public function testRemove(): void
    {
        $this->tester
            ->assertArr([], Arr::remove([], '', 'id'));
    }

    public function testBlacklist(): void
    {
        $this->tester
            ->assertArr([], Arr::blacklist([]))
            ->assertArr([], Arr::blacklist(['id' => 1, 'name' => '', 'other' => 2], 'id', 'name', 'other'));
    }

    public function testWhitelist(): void
    {
        $this->tester
            ->assertArr(['id' => 1, 'some' => 2], Arr::whitelist(['id' => 1, 2, 3, 'many' => [], 'some' => 2], 'id', 'some'));
    }

    public function testRemoveFirst(): void
    {
        $this->tester
            ->assertArr(null, Arr::removeFirst([]))
            ->assertArr(null, Arr::removeFirst([null]))
            ->assertArr(1, Arr::removeFirst([1, 2, 3]));
    }

    public function testRemoveLast(): void
    {
        $this->tester
            ->assertArr(null, Arr::removeLast([]))
            ->assertArr(null, Arr::removeLast([null]))
            ->assertArr(3, Arr::removeLast([1, 2, 3]));
    }
}