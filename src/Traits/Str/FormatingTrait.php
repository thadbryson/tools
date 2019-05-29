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
     *
     * @param string $locale
     * @param string $format
     * @return $this
     */
    public function money(string $locale = 'en_US', string $format = '%n')
    {
        Assert::numeric($this->get(), '$var must be a numeric string, integer, or float.');
        setlocale(LC_MONETARY, $locale . '.' . $this->getEncoding());

        $this->str = money_format($format, (float) $this->get());

        return $this;
    }

    /**
     * Get international money string format.
     *
     * @param string $locale
     * @return Str
     */
    public function moneyInternational(string $locale = 'en_US')
    {
        return $this->money($locale, '%i');
    }

    /**
     * Format to temperature. Ex: '75 &deg; F'
     *
     * @param bool $html       = true
     * @param bool $fahrenheit = true
     * @return $this
     */
    public function temperature(bool $html = true, bool $fahrenheit = true)
    {
        $sign = $html ? '&deg; ' : '° ';
        $sign .= $fahrenheit ? 'F' : 'C';

        $this->str .= $sign;

        return $this;
    }
}