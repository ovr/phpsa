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
    public function testGreaterEqualTrue()
    {
        $a = 1;
        return $a >= 0;
    }

    /**
     * @return bool
     */
    public function testGreaterEqualFalse()
    {
        $a = 1;
        return $a >= 2;
    }

    public function testSmallerTrue()
    {
        $a = 5;
        return $a < 6;
    }

    public function testSmallerFalse()
    {
        $a = 5;
        return $a < 3;
    }

    public function testSmallerEqualTrue()
    {
        $a = 5;
        return $a <= 6;
    }

    public function testSmallerEqualFalse()
    {
        $a = 5;
        return $a <= 3;
    }
}
