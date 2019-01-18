<?php

declare(strict_types = 1);

namespace Tool\Support\Traits\Collection;

use Illuminate\Support\Collection as BaseCollection;
use Tool\Support\Arr;
use Tool\Support\Collection;
use Tool\Validation\Assert;

/**
 * Class TableTrait
 *
 * @mixin Collection
 */
trait TableTrait
{
    public function tableFromRow(int $rowNumber = 0): BaseCollection
    {
        $keys = $this->getRowNumber($rowNumber);

        Assert::isArray($keys, sprintf('Row found at position "%s" was not an array.', $rowNumber));

        /** @var array $keys */
        $keys = array_values($keys);

        return $this->tableKeys($keys);
    }

    public function tableKeys(array $keys): BaseCollection
    {
        return $this->map(function (array $array) use ($keys) {

            $array = array_values($array);

            foreach ($keys as $index => $key) {
                $array[$key] = Arr::pull($array, $index);
            }

            return $array;
        });
    }
}