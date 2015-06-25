<?php

namespace Simple\CodeSmell;

class Cast
{
    /**
     * Boolean cast (bool)
     */

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

    /**
     * Int cast (int)
     */

    public function testCastIntFromInt()
    {
        return (int) 123456789;
    }

    public function testCastIntFromFloat()
    {
        return (int) 123456789.5;
    }

    public function testCastIntFromBooleanTrue()
    {
        return (int) true;
    }

    public function testCastIntFromBooleanFalse()
    {
        return (int) false;
    }

    public function testCastIntFromString()
    {
        return (int) "test string";
    }

    public function testCastIntFromEmptyString()
    {
        return (int) "";
    }

    /**
     * Float cast (float)
     */

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

    public function testCastFloatFromEmptyString()
    {
        return (float) "";
    }

    public function testCastFloatFromString()
    {
        return (float) "test string";
    }

    /**
     * String cast (string)
     */

    public function testCastStringFromEmptyString()
    {
        return (string) "";
    }

    public function testCastStringFromString()
    {
        return (string) "test string";
    }

    public function testCastStringFromInt()
    {
        return (string) 12345;
    }

    public function testCastStringFromFloat()
    {
        return (string) 25.2525;
    }

    public function testCastStringFromBooleanTrue()
    {
        return (string) true;
    }

    public function testCastStringFromBooleanFalse()
    {
        return (string) true;
    }

    /**
     * Unset cast (unset)
     */

    public function testCastUnsetFromBooleanTrue()
    {
        return (unset) true;
    }

    public function testCastUnsetFromBooleanFalse()
    {
        return (unset) false;
    }

    public function testCastUnsetFromNull()
    {
        return (unset) null;
    }
}