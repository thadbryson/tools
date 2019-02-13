<?php

declare(strict_types = 1);

namespace Tool;

use Illuminate\Support\Pluralizer;
use Tool\Traits\Str as StrTraits;
use Tool\Validation\Assert;
use function json_decode;
use function json_last_error;
use function strlen;
use const JSON_ERROR_NONE;

/**
 * Class Str
 *
 */
class Str extends \Stringy\Stringy
{
    use StrTraits\BooleanTraits,
        StrTraits\StaticMakeTrait;

    /**
     * Explode string into an array.
     */
    public function explode(string $delimiter): array
    {
        return explode($delimiter, $this->get());
    }

    public function get(): string
    {
        return $this->__toString();
    }

    public function swapLeft(string $substr, string $replace): self
    {
        if ($this->startsWith($substr) === false) {
            return $this;
        }

        return $this
            ->removeLeft($substr)
            ->prepend($replace);
    }

    public function swapRight(string $substr, string $replace): self
    {
        return $this
            ->removeRight($substr)
            ->append($replace);
    }

    public function beforeSubstr(string $substr, int $offset = 0): self
    {
        $index = $this->indexOf($substr, $offset);

        return $this->substr(0, $index);
    }

    public function afterSubstr(string $substr, int $offset = 0): self
    {
        $index = $this->indexOf($substr, $offset);
        $index += strlen($substr);

        return $this->substr($index);
    }

    public function jsonDecode()
    {
        return $this->jsonDecodeOptions(true);
    }

    public function jsonDecodeOptions(bool $assoc = false, int $options = 0, int $depth = 512)
    {
        $decoded = json_decode($this->str, $assoc, $depth, $options);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('String is not valid JSON: ' . $this->get());
        }

        return $decoded;
    }

    /**
     * Get the plural form of an English word.
     */
    public function plural(int $count = 2): self
    {
        $this->str = Pluralizer::plural($this->str, $count);

        return $this;
    }

    public function getter(string $append = ''): self
    {
        return $this->getAsMethod('get', $append);
    }

    public function setter(string $append = ''): self
    {
        return $this->getAsMethod('set', $append);
    }

    public function hasser(string $append = ''): self
    {
        return $this->getAsMethod('has', $append);
    }

    public function isser(string $append = ''): self
    {
        return $this->getAsMethod('is', $append);
    }

    /**
     * Get monetery string format.
     */
    public function money(string $locale = 'en_US', string $format = '%n'): self
    {
        Assert::numeric($this->get(), '$var must be a numeric string, integer, or float.');
        setlocale(LC_MONETARY, $locale . '.' . $this->getEncoding());

        $this->str = money_format($format, (float) $this->get());

        return $this;
    }

    /**
     * Get international monetery format.
     */
    public function moneyInternational(string $locale = 'en_US'): self
    {
        return $this->money($locale, '%i');
    }

    protected function getAsMethod(string $prepend, string $append): self
    {
        return $this->replace('_', ' ')
            ->prepend($prepend . ' ')
            ->append(' ' . $append)
            ->titleize()
            ->lowerCaseFirst()
            ->replace(' ', '');
    }
}
