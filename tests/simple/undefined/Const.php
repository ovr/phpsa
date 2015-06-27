<?php

namespace Tests\Simple\Undefined;

/**
 * Class UndefinedConst
 * @package Tests\Simple\Undefined
 */
class UndefinedConst
{
    /**
     * @var int
     */
    const A = 1;

    /**
     * @return int
     */
    public function testA()
    {
        return self::A;
    }

    /**
     * @return mixed
     */
    public function testUndefinedConst()
    {
        return self::BBBB;
    }
}
