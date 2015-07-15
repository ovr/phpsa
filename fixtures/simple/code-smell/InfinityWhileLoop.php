<?php

namespace Tests\Simple\CodeSmell;

/**
 * Class InfinityWhileLoop
 * @package Tests\Simple\CodeSmell
 */
class InfinityWhileLoop
{
    /**
     * @return bool
     */
    public function testInfinityEmptyWhileLoop1()
    {
        while (true) {

        }

        return true;
    }

    /**
     * @return bool
     */
    public function testInfinityEmptyWhileLoop2()
    {
        $a = 3;

        while ($a > 2) {

        }

        return true;
    }

    /**
     * @return bool
     */
    public function testInfinityEmptyWhileLoop3()
    {
        $a = 0;

        while ($a < 100) {

        }

        return true;
    }

    /**
     * @return bool
     */
    public function testNotInfinityEmptyWhileLoop3()
    {
        $a = 0;

        while ($a < 100) {
            $a++;
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function alwaysTrue()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function testInfinityEmptyWhileLoopAlwaysTrueExpr()
    {
        $a = 0;

        while ($this->alwaysTrue()) {
            $a++;
        }

        return true;
    }
}
