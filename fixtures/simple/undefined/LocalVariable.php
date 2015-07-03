<?php

namespace Tests\Simple\Undefined;

class UndefinedVar
{
    /**
     * @return int
     */
    public function test1()
    {
        $a = ((1 + 2 + 3 + 4 - 2) / 5) * 6 ^ 2;
        return $a + $b;
    }
}
