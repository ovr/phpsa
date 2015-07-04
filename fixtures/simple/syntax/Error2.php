<?php

namespace Tests\Simple\Syntax;

class Error2
{
    public function a()
    {
        $a = 1;
        $b = $a + 1; 123123
        return $b;
    }
}
