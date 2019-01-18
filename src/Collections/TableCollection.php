<?php

declare(strict_types = 1);

namespace Tool\Support\Collections;

use Tool\Validation\Assert;
use function array_combine;
use function json_encode;

/**
 * Class TableCollection
 */
class TableCollection
{
    /**
     * @var Collection
     */
    protected $items;

    /**
     * @var array
     */
    protected $headers;

    public function __construct(array $items = [])
    {
        $this->items = new Collection($items);
    }

    public static function make(array $items = []): self
    {
        return new static($items);
    }

    public function toArray(): array
    {
        return $this->items
            ->map(function (array $row) {

                return array_combine($this->headers, $row);
            })
            ->all();
    }

    public function toJson(int $options): string
    {
        return json_encode($this->toArray(), $options);
    }

    public function get(int $column, int $row)
    {
        $result = $this->getRow($row);

        return $result[$column] ?? null;
    }

    public function hasHeaders(): bool
    {
        return $this->headers !== [];
    }

    public function setHeaders(string ...$headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    public function getHeader(int $headerNumber): ?string
    {
        return $this->headers[$headerNumber] ?? null;
    }

    public function hasHeader(int $headerNumber): bool
    {
        return $this->getHeader($headerNumber) !== null;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function addRow(string ...$row): self
    {
        $this->items[] = $row;

        return $this;
    }

    public function getRow(int $number): ?array
    {
        Assert::min($number, 0, '0 is the minimum (first) row number.');

        $row = $this->items->get($number);

        if ($row === null || $this->hasHeaders() === false) {
            return $row;
        }

        // Set "header" keys for row.
        return array_combine($this->headers, $row);
    }

    public function getRowMany(int $fromNumber, int $toNumber): array
    {
        $rows = [];

        for ($rowNumber = $fromNumber;$rowNumber < $toNumber;$rowNumber++) {
            $rows[$rowNumber] = $this->getRow($rowNumber);
        }

        return $rows;
    }

    public function setRow(int $number, string ...$row): self
    {
        $this->items[$number] = $row;

        return $this;
    }

    public function removeRow(int $number): self
    {
        $this->items->remove([$number]);

        return $this;
    }

    public function hasRow(int $number): bool
    {
        return $number >= 0 && $number < $this->count();
    }

    public function count(): int
    {
        return $this->items->count();
    }
}