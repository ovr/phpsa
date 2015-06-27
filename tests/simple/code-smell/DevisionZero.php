<?php

namespace Tests\Simple\CodeSmell;

class DevisionZero
{
    /**
     * @return float
     */
    public function testDivisionOnZero1()
    {
        return 1000 / 0;
    }

    /**
     * @return float
     */
    public function testDivisionOnZero2()
    {
        return 1000 / (100-100);
    }

    /**
     * @return float
     */
    public function testDivisionOnZero3()
    {
        return 1000 / ((50+50)-100);
    }

    /**
     * @return float
     */
    public function testDivisionOnZero4()
    {
        return 1000 / ((5*5)-25);
    }

    /**
     * @return float
     */
    public function testDivisionOnZero5()
    {
        return 1000 / ((-25) + (5*5));
    }

    /**
     * @return float
     */
    public function testDivisionOnZero6()
    {
        return 1000 / ((-4) + (5^1));
    }

    /**
     * @return float
     */
    public function testDivisionFromZero()
    {
        return 0 / 1000;
    }

    /**
     * @return float
     */
    public function testDivision()
    {
        return 10/25;
    }
}
