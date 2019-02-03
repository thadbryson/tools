<?php

declare(strict_types = 1);

namespace Tool\Filesystem\Files;

use Tool\Collection;
use Tool\Filesystem\File;
use function is_array;

/**
 * Class CsvFile
 *
 * File of type .csv.
 */
class CsvFile extends File
{
    /**
     * Separates columns.
     *
     * @var string
     */
    protected $delimiter = ',';

    /**
     * Encloses cell values.
     *
     * @var string
     */
    protected $enclosure = '"';

    /**
     * Escape character.
     *
     * @var string
     */
    protected $escape = "\\";

    public function __construct(string $path)
    {
        parent::__construct($path);

        $this->assertExtension('csv');
    }

    public static function make($path, string $delimiter = ',', string $enclosure = '"', string $escape = "\\"): CsvFile
    {
        return parent::make($path)
            ->setDelimiter($delimiter)
            ->setEnclosure($enclosure)
            ->setEscape($escape);
    }

    public function toCollection(int $lineLength = 1000): Collection
    {
        $this->assertReadable();

        $items = [];
        $row   = [];

        $fopen = $this->open();

        while (is_array($row) === true) {

            $row = fgetcsv($fopen, $lineLength, $this->getDelimiter(), $this->getEnclosure(), $this->getEscape());

            if (is_array($row) === true) {
                $items[] = $row;
            }
        }

        $this->close();

        return new Collection($items);
    }

    /**
     * @return string
     */
    public function getDelimiter(): string
    {
        return $this->delimiter;
    }

    /**
     * @param string $delimiter
     */
    public function setDelimiter(string $delimiter): self
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    /**
     * @return string
     */
    public function getEnclosure(): string
    {
        return $this->enclosure;
    }

    /**
     * @param string $enclosure
     */
    public function setEnclosure(string $enclosure): self
    {
        $this->enclosure = $enclosure;

        return $this;
    }

    /**
     * @return string
     */
    public function getEscape(): string
    {
        return $this->escape;
    }

    /**
     * @param string $escape
     */
    public function setEscape(string $escape): self
    {
        $this->escape = $escape;

        return $this;
    }
}
