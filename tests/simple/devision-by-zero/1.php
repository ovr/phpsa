<?php

class DevisionByZero
{
    public function testDivisionOnZero()
    {
        return 1000 / 0;
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
