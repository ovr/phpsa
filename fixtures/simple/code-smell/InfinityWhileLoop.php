<?php

namespace Tests\Simple\CodeSmell;

/**
 * Class InfinityWhileLoop
 * @package Tests\Simple\CodeSmell
 */
class InfinityWhileLoop
{
    public function testInfinityEmptyWhileLoop1()
    {
        while (true) {

        }
    }

    public function testInfinityEmptyWhileLoop2()
    {
        $a = 3;

        while ($a > 2) {

        }
    }


    public function testInfinityEmptyWhileLoop3()
    {
        $a = 0;

        while ($a < 100) {

        }
    }

    public function testNotInfinityEmptyWhileLoop3()
    {
        $a = 0;

        while ($a < 100) {
            $a++;
        }
    }
}
