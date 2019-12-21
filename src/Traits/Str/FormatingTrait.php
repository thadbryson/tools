<?php

declare(strict_types = 1);

namespace Tool\Traits\Str;

use Tool\Str;
use Tool\Validation\Assert;

/**
 * Trait MoneyTrait
 *
 * @mixin Str
 */
trait FormatingTrait
{
    /**
     * Get money string formatted.
     */
    public function money(string $locale = 'en_US', string $format = '%n'): self
    {
        Assert::numeric($this->get(), '$var must be a numeric string, integer, or float.');
        setlocale(LC_MONETARY, $locale . '.' . $this->getEncoding());

        $this->str = money_format($format, (float) $this->get());

        return $this;
    }

    /**
     * Get international money string format.
     */
    public function moneyInternational(string $locale = 'en_US'): self
    {
        return $this->money($locale, '%i');
    }

    /**
     * Format to temperature. Ex: '75 &deg; F'
     */
    public function temperature(bool $html = true, bool $fahrenheit = true): self
    {
        $sign = $html ? '&deg; ' : '° ';
        $sign .= $fahrenheit ? 'F' : 'C';

        $this->str .= $sign;

        return $this;
    }

    /**
     * Convert hex color codes to RGB.
     *
     * @return int[]|null
     */
    public function colorHexToRgb(): ?array
    {
        $str = $this->trim()->trimLeft('#');

        if ($str->length() === 3) {
            $char1 = $str->at(0);
            $char2 = $str->at(1);
            $char3 = $str->at(2);

            $str = new Str("{$char1}{$char1}{$char2}{$char2}{$char3}{$char3}");
        }

        if ($str->length() !== 6 || $str->isHexadecimal() === false) {
            return null;
        }

        $red   = $str->substr(0, 2)->get();
        $green = $str->substr(2, 2)->get();
        $blue  = $str->substr(4, 2)->get();

        return [
            'red'   => (int) hexdec($red),
            'green' => (int) hexdec($green),
            'blue'  => (int) hexdec($blue)
        ];
    }

    /**
     * Format to a US phone number. (xxx) xxx-xxxx
     */
    public function phone(): self
    {
        $phone = $this->str;

        // Valid phone number?
        if (strlen($phone) === 12 && substr($phone, 0, 2) === '+1') {
            $this->str = sprintf('(%s) %s-%s', substr($phone, 2, 3), substr($phone, 5, 3), substr($phone, 8));
        }
        else {
            $this->str = '';
        }

        return $this;
    }
}