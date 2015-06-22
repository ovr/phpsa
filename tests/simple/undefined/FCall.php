<?php

class Test
{
    public function failedCallToUndefinedFunction()
    {
        undefinedFunction();
    }

    public function successStrRepeat()
    {
        return str_repeat("*", 10);
    }
}
