<?php

declare(strict_types = 1);

use _generated\UnitTesterActions;
use Tool\Support\Clock;
use Tool\Support\Collection;
use Tool\Support\Str;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class UnitTester extends \Codeception\Actor
{
    use UnitTesterActions;

    /**
     * Define custom actions here
     */
    public function assertStr(Str $str, string $expected, string $encoding = null): self
    {
        $encoding = $encoding ?? \mb_internal_encoding();

        $this->assertInstanceOf(Str::class, $str, 'Must be object instance of ' . Str::class);

        $this->assertEquals($expected, $str->get());
        $this->assertEquals($expected, $str->__toString());
        $this->assertEquals($expected, (string) $str);

        $this->assertEquals($encoding, $str->getEncoding());

        return $this;
    }

    public function assertArr($expected, $result, string $message = ''): self
    {
        if (is_object($expected) && method_exists($expected, 'toArray')) {
            $expected = $expected->toArray();
        }

        if (is_object($result) && method_exists($result, 'toArray')) {
            $result = $result->toArray();
        }

        $this->assertEquals($expected, $result, sprintf('%s, with array: %s', $message, json_encode($result)));

        return $this;
    }

    public function assertRestrictedCollection($expected, Collection $coll, string $message = ''): self
    {
        $this->assertArr($expected, $coll, $message);

        $coll->assert();

        return $this;
    }

    public function assertClock(DateTime $datetime, Clock $clock, string $message = ''): self
    {
        $this->assertEquals($datetime->format('Y-m-d H:i:s'), $clock->format('Y-m-d H:i:s'), $message);

        return $this;
    }
}
