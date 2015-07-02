<?php

namespace Tests\Simple\CodeSmell;

/**
 * Class Cast
 * @package Tests\Simple\CodeSmell
 */
class Cast
{
    /**
     * Boolean cast (bool)
     */

    /**
     * @return bool
     */
    public function testCastBooleanTrue()
    {
        return (bool) true;
    }

    /**
     * @return bool
     */
    public function testCastBooleanFalse()
    {
        return (bool) false;
    }

    /**
     * @return bool
     */
    public function testCastBooleanFromEqualStaticCondition()
    {
        return (bool) ([] == []);
    }

    /**
     * @return bool|int
     */
    public function testAssignCastBooleanFalse()
    {
        $a = 123456789;
        $a = (bool) false;

        return $a;
    }

    /**
     * Int cast (int)
     */

    /**
     * @return int
     */
    public function testCastIntFromInt()
    {
        return (int) 123456789;
    }

    /**
     * @return int
     */
    public function testCastIntFromFloat()
    {
        return (int) 123456789.5;
    }

    /**
     * @return int
     */
    public function testCastIntFromBooleanTrue()
    {
        return (int) true;
    }

    /**
     * @return int
     */
    public function testCastIntFromBooleanFalse()
    {
        return (int) false;
    }

    /**
     * @return int
     */
    public function testCastIntFromString()
    {
        return (int) "test string";
    }

    /**
     * @return int
     */
    public function testCastIntFromEmptyString()
    {
        return (int) "";
    }

    /**
     * Float cast (float)
     */

    /**
     * @return float
     */
    public function testCastFloatFromInt()
    {
        return (float) 123456789;
    }

    /**
     * @return float
     */
    public function testCastFloatFromFloat()
    {
        return (float) 123456789.5;
    }

    /**
     * @return float
     */
    public function testCastFloatFromBooleanTrue()
    {
        return (float) true;
    }

    /**
     * @return float
     */
    public function testCastFloatFromBooleanFalse()
    {
        return (float) false;
    }

    /**
     * @return float
     */
    public function testCastFloatFromEmptyString()
    {
        return (float) "";
    }

    /**
     * @return float
     */
    public function testCastFloatFromString()
    {
        return (float) "test string";
    }

    /**
     * String cast (string)
     */

    /**
     * @return string
     */
    public function testCastStringFromEmptyString()
    {
        return (string) "";
    }

    /**
     * @return string
     */
    public function testCastStringFromString()
    {
        return (string) "test string";
    }

    /**
     * @return string
     */
    public function testCastStringFromInt()
    {
        return (string) 12345;
    }

    /**
     * @return string
     */
    public function testCastStringFromFloat()
    {
        return (string) 25.2525;
    }

    /**
     * @return string
     */
    public function testCastStringFromBooleanTrue()
    {
        return (string) true;
    }

    /**
     * @return string
     */
    public function testCastStringFromBooleanFalse()
    {
        return (string) true;
    }

    /**
     * Unset cast (unset)
     */

    /**
     * @return null
     */
    public function testCastUnsetFromBooleanTrue()
    {
        return (unset) true;
    }

    /**
     * @return null
     */
    public function testCastUnsetFromBooleanFalse()
    {
        return (unset) false;
    }

    /**
     * @return null
     */
    public function testCastUnsetFromNull()
    {
        return (unset) null;
    }
}
