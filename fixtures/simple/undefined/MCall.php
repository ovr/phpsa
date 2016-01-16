<?php

namespace Tests\Simple\Undefined;

class MCall
{
    /**
     * @return boolean
     */
    static public function simpleStaticMethod()
    {
        return true;
    }

    /**
     * @return boolean
     */
    public function simpleAlwaysTrueReturnMethod()
    {
        return true;
    }

    /**
     * @return mixed
     */
    public function a()
    {
        return $this->bfdf();
    }

    /**
     * @return mixed
     */
    public function c()
    {
        return $this->a();
    }

    /**
     * @param $var1
     * @return mixed
     */
    public function testMethodWithOneRequiredParameter($var1)
    {
        return $var1 + 1;
    }

    /**
     * @param $var1
     * @return bool
     */
    public function testMethodWithOneRequiredParameterWithoutUsing($var1)
    {
        return true;
    }

    /**
     * @return string
     */
    public function testSuccessProperty()
    {
        $property = new Property();
        return $property->b();
    }

    /**
     * @return mixed
     */
    public function testCallFromUndefinedVariable()
    {
        return $undefinedVariable->b();
    }

    /**
     * @return mixed
     */
    public function testUnexpectedCallFromArrayVariable()
    {
        $arrayVariable = array();

        return $arrayVariable->arrayMethod();
    }

    /**
     * @return mixed
     */
    public function testUnexpectedCallFromIntVariable()
    {
        $intVariable = 1;

        return $intVariable->intMethod();
    }

    /**
     * @return mixed
     */
    public function testUnexpectedCallFromFloatVariable()
    {
        $floatVariable = 1;

        return $floatVariable->FloatMethod();
    }

    /**
     * @return mixed
     */
    public function testCallFromObjectVariable()
    {
        $thisPointer = $this;

        return $thisPointer->simpleAlwaysTrueReturnMethod();
    }

    /**
     * @return mixed
     */
    public function testUnexpectedCallOnStaticMethod()
    {
        return $this->simpleStaticMethod();
    }
}
