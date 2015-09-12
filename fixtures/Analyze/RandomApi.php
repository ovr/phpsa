<?php

namespace Tests\Compiling\Statements;

/**
 * Class Do_
 */
class RandomApi
{
    /**
     * @return integer
     */
    public function test1()
    {
        return rand(0, 100);
    }
}
