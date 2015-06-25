<?php

namespace Tests\Simple\CodeSmell;

class DevisionZero
{
    public function testDivisionOnZero1()
    {
        return 1000 / 0;
    }

    public function testDivisionOnZero2()
    {
        return 1000 / (100-100);
    }

    public function testDivisionOnZero3()
    {
        return 1000 / ((50+50)-100);
    }

    public function testDivisionOnZero4()
    {
        return 1000 / ((5*5)-25);
    }

    public function testDivisionOnZero5()
    {
        return 1000 / ((-25) + (5*5));
    }

    public function testDivisionOnZero6()
    {
        return 1000 / ((-4) + (5^1));
    }

    public function testDivisionFromZero()
    {
        return 0 / 1000;
    }

    public function testDivision()
    {
        return 10/25;
    }
}
