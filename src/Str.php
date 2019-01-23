<?php

declare(strict_types = 1);

namespace Tool\Support;

use Illuminate\Support\Pluralizer;
use Tool\Support\Traits\Str as StrTraits;
use Tool\Validation\Assert;
use function json_decode;
use function json_last_error;
use function strlen;
use function tool\functions\string\money;
use function tool\functions\string\money_international;
use const JSON_ERROR_NONE;

/**
 * Class Str
 */
class Str extends \Stringy\Stringy
{
    use StrTraits\BooleanTraits,
        StrTraits\StaticMakeTrait;

    public function get(): string
    {
        return $this->__toString();
    }

    /**
     * Explode string into an array.
     */
    public function explode(string $delimeter): array
    {
        Assert::stringNotEmpty($delimeter, '$delimeter cannot be an empty string.');

        return explode($delimeter, $this->get());
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

    private function getAsMethod(string $prepend, string $append): self
    {
        return $this->replace('_', ' ')
                    ->prepend($prepend . ' ')
                    ->append(' ' . $append)
                    ->titleize()
                    ->lowerCaseFirst()
                    ->replace(' ', '');
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
    public function money(string $locale = 'en_US'): self
    {
        $this->str = money($this->get(), $locale, $this->getEncoding());

        return $this;
    }

    /**
     * Get international monetery format.
     */
    public function moneyInternational(string $locale = 'en_US'): self
    {
        $this->str = money_international($this->get(), $locale, $this->getEncoding());

        return $this;
    }
}
