<?php

namespace Tests\Simple\Undefined;

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
     * @return string
     */
    public function successStrRepeat()
    {
        return str_repeat("*", 10);
    }
}
