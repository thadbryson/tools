<?php

declare(strict_types = 1);

namespace Tool\Imports;

use Illuminate\Support\Collection as BaseCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Tool\Collection;

/**
 * Class BasicImport
 */
class BasicImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use Importable,
        SkipsErrors,
        SkipsFailures;

    public function collection(BaseCollection $collection): Collection
    {
        return new Collection($collection->all());
    }

    public function rules(): array
    {
        return [];
    }
}
