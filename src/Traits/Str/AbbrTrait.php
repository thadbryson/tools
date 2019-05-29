<?php

declare(strict_types = 1);

namespace Tool\Traits\Str;

use Tool\Str;

/**
 * Trait AbbrTrait
 *
 * @mixin Str
 */
trait AbbrTrait
{
    /**
     * If string is longer than $length then shorten and add $append string at end.
     *
     * @param int    $length
     * @param string $append = '...'
     * @return $this
     */
    public function limit(int $length, string $append = '...')
    {
        $this->str = \Illuminate\Support\Str::limit($this->str, $length, $append);

        return $this;
    }

    /**
     * Return shortened text in <abbr> tag.
     *
     * @param int    $length
     * @param string $append = '...
     * @return $this
     */
    public function abbr(int $length, string $append = '...')
    {
        if ($length < strlen($this->str)) {
            $full = $this->get();
            $str  = $this->limit($length, $append);

            $this->str = sprintf('<abbr title="%s">%s</abbr>', $full, $str);
        }

        return $this;
    }

    /**
     * Use <abbr> text with different text placeholder.
     *
     * @param string $title
     * @param int    $length
     * @param string $append = '...'
     * @return $this
     */
    public function abbrTitle(string $title, int $length, string $append = '...')
    {
        $str = $this->limit($length, $append);

        $this->str = sprintf('<abbr title="%s">%s</abbr>', $title, $str);

        return $this;
    }
}
