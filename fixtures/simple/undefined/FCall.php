<?php

namespace Tests\Simple\Undefined;

/**
 * @return bool
 */
function testFunction()
{
    return true;
}

/**
 * Class Test
 * @package Tests\Simple\Undefined
 */
class Test
{
    /**
     * @return mixed
     */
    public function failedCallToUndefinedFunction()
    {
        return undefinedFunction();
    }

    /**
     * @return mixed
     */
    public function successCallFromNSFunction()
    {
        return testFunction();
    }

    /**
     * @return string
     */
    public function successStrRepeat()
    {
        return str_repeat("*", 10);
    }

    /**
     * @return double
     */
    public function successSin()
    {
        return sin(1);
    }
}
