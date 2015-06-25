<?php

namespace Simple\CodeSmell;

class Cast
{
    public function testCastBooleanTrue()
    {
        return (bool) true;
    }

    public function testCastBooleanFalse()
    {
        return (bool) false;
    }

    public function testAssignCastBooleanFalse()
    {
        $a = 123456789;
        $a = (bool) false;

        return $a;
    }

    public function testCastIntFromInt()
    {
        return (int) 123456789;
    }

    public function testCastIntFromFloat()
    {
        return (int) 123456789.5;
    }

    public function testCastFloatFromInt()
    {
        return (float) 123456789;
    }

    public function testCastFloatFromFloat()
    {
        return (float) 123456789.5;
    }

    public function testCastFloatFromBooleanTrue()
    {
        return (float) true;
    }

    public function testCastFloatFromBooleanFalse()
    {
        return (float) false;
    }

    public function testCastFloatFromStringEmpty()
    {
        return (float) "";
    }

    public function testCastFloatFromString()
    {
        return (float) "test string";
    }
}