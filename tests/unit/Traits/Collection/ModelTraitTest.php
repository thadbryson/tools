<?php

declare(strict_types = 1);

namespace Tests\Unit\Traits\Collection;

use Assert\InvalidArgumentException;
use Tests\Support\Stubs\ModelStub;
use Tests\Support\Stubs\UserStub;
use Tool\Collection;

/**
 * Trait ModelTrait
 *
 * @mixin Collection
 */
class ModelTraitTest extends \Codeception\Test\Unit
{
    public function testSaveAll(): void
    {
        $coll = Collection::make([
            new UserStub,
            new UserStub,
            new UserStub
        ])->saveAll();

        $coll->each(function (UserStub $stub) {

            $this->assertEquals('save', $stub->getAction());
        });
    }

    public function testDeleteAll(): void
    {
        $coll = Collection::make([
            new UserStub,
            new UserStub,
            new UserStub
        ])->deleteAll();

        $coll->each(function (UserStub $stub) {

            $this->assertEquals('delete', $stub->getAction());
        });
    }

    public function testDeleteNot(): void
    {
        $model1 = new ModelStub;
        $model2 = new ModelStub;
        $model3 = new ModelStub;

        $model1->id = 1;
        $model2->id = 2;
        $model3->id = 3;

        $coll = Collection::make([$model1, $model2, $model3]);

        $coll->deleteNot();

        $coll[0] = false;
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('First element in collection is not a Illuminate\Database\Eloquent\Model class object. ' .
            'Must be unless you specify a Illuminate\Database\Eloquent\Builder object in the first argument.');

        $coll->deleteNot();
    }
}
