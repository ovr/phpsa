<?php

/**
 * Class Test
 */
class Test
{
    /**
     * @return bool
     */
    public function returnTrue()
    {
        $a = new stdClass();
//        $a->test = 1;

        $b = $a;

        return $a == $b;
    }
}
