<?php

namespace Tests\Compiling\Statements;

/**
 * Class Do_
 */
class Do_
{
    /**
     * @problem: Ininity loop
     *
     * @return void
     */
    public function test1()
    {
        do {
            //missing body
        } while(true);
    }

    /**
     * @problem: Not a loop
     *
     * @return boolean
     */
    public function test2()
    {
        do {
            return true;
        } while(true);

        //unreachable code
        return false;
    }

    /**
     * @problem: Infinity loop
     *
     * @return boolean
     */
    public function test3($a)
    {
        do {
            //missing body
        } while($a < 100);

        return false;
    }

    /**
     * @problem: Infinity loop
     *
     * @return boolean
     */
    public function test5($a)
    {
        do {
            //it's need a plus not minus
            $a--;
        } while($a < 100);

        return false;
    }


    /**
     * @return boolean
     */
    public function testSuccessLoop($a)
    {
        do {
            $a++;
        } while($a < 100);

        return false;
    }
}
