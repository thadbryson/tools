<?php

declare(strict_types = 1);

namespace Tests\Support\Stubs;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Mockery;

class ModelStub extends Model
{
    public function newQuery()
    {
        $mock = Mockery::mock(Builder::class);

        $mock->shouldReceive('whereNotIn')->andReturn($mock);
        $mock->shouldReceive('delete');

        return $mock;
    }
}