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
    public function testSuccess1()
    {
        for ($i = 0; $i < 100; $i++) {
            $i++;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function testEmptyBody1()
    {
        for ($i = 0; $i < 100; $i++) {

        }

        return true;
    }
}
