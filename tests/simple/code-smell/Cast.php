<?php

namespace Simple\CodeSmell;

class Cast
{
    public function testCostBooleanTrue()
    {
        return (bool) true;
    }

    public function testCostBooleanFalse()
    {
        return (bool) false;
    }

    public function testAssignBooleanFalse()
    {
        $a = 123456789;
        $a = (bool) false;

        return $a;
    }
}