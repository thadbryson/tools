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

    public function testRemoveFirst(): void
    {
        $array = [1, 2, 3];

        $this->tester
            ->assertArr(1, Arr::removeFirst($array))
            ->assertArr(2, Arr::removeFirst($array))
            ->assertArr(3, Arr::removeFirst($array));

        $this->assertEquals([], $array);
        $this->assertNull(Arr::removeFirst($array));
    }

    public function testRemoveLast(): void
    {
        $array = [1, 2, 3];

        $this->tester
            ->assertArr(3, Arr::removeLast($array))
            ->assertArr(2, Arr::removeLast($array))
            ->assertArr(1, Arr::removeLast($array));

        $this->assertEquals([], $array);
        $this->assertNull(Arr::removeLast($array));
    }
}