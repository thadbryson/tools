<?php

declare(strict_types = 1);

namespace Tests\Unit;

use Tool\SheetImport;

class SheetImportTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function test(): void
    {
        $rows = [
            [],
            [],
        ];

        // Load rows (arrays)
        $import = SheetImport::make()
            ->setHeaders('First Name', 'Last Name', 'Age')// Set by order index => header to assign on each row
            ->setHeaderMap([
                'col1' => 'First Name',
                'col2' => 'Last Name',
                'col3' => 'Age',
                'col4' => 'E-mail',
            ])
            ->addRow([])
            ->addRows(...$rows)
            ->setRowRules([
                'First Name' => 'required|string|min:3',
                'Age'        => 'required|integer|min:13',
            ])
            ->setTrimAll()// trim all values & headers
            ->setRemoveEmptyRows(true)// Remove empty rows
            ->execute();        // validate rows,

        $processor = Processor::make($import);

        // Parse any files (Excel, CSV, or from POST)
        // use header row to map each row?
        // set headers manually
        // remove any empty rows?
        // validate all rows, set Result object
        // remove invalid rows?
        // process all rows - turn to objects, change arrays, etc
        // filterInvalid() - remove invalid rows
        // filterValids() - get valid rows
        // filter() - filter with a custom callback
        // log rows - row number, description, error messages, etc
        // process each row with a callback

        // Use a class with __invoke() method???
        /*

        validate()
        getValidation(): Result
        isCreated(): bool
        isUpdated(): bool
        isDeleted(): bool
        transform(array $row)
        getRowNumber(): int 
         */
    }
}
