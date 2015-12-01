<?php

namespace Tests\Compiling\Statements;

class RandomApi
{
    /**
     * @return integer
     */
    public function test1()
    {
        return rand(0, 100);
    }

    /**
     * @return integer
     */
    public function test2()
    {
        srand();
    }

    /**
     * @return integer
     */
    public function test3()
    {
        return getrandmax();
    }
}
