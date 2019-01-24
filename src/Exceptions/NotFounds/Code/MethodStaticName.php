<?php
declare(strict_types = 1);

namespace Tool\Exceptions\NotFounds;

class MethodStaticName extends MethodName
{
    /**
     * @inheritdoc
     */
    protected $title = 'Static Method Not Found: ';
}
