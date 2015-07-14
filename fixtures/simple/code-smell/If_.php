<?php

namespace Tests\Simple\CodeSmell;

/**
 * Class If_
 * @package Tests\Simple\CodeSmell
 */
class If_
{
    /**
     * @return bool
     */
    public function testGreaterTrue()
    {
        $a = 1;
        return $a > 0;
    }

    /**
     * @return bool
     */
    public function testGreaterFalse()
    {
        $a = 1;
        return $a > 2;
    }

    /**
     * @return bool
     */
    public function testGreaterSameTrue()
    {
        $a = 1;
        return $a >= 0;
    }

    /**
     * @return bool
     */
    public function testGreaterSameFalse()
    {
        $a = 1;
        return $a >= 2;
    }
}
