<?php

namespace Test\Simple\Undefined;

class MCall
{
    public function a()
    {
        return $this->b();
    }

    public function c()
    {
        return $this->a();
    }

    public function testMethodWithOneRequiredParameter($var1)
    {
        return $var1 + 1;
    }

    public function testMethodWithOneRequiredParameterWithoutUsing($var1)
    {
        return true;
    }

    public function testSuccessProperty()
    {
        $property = new Property();
        return $property->b();
    }

    public function testCallFromUnusedVariable()
    {
        return $unusedVariable->b();
    }
}
