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
     * @param $a
     */
    public function testBreakIsNotNeeded($a)
    {
        switch ($a) {
            case 1:
                return true;
                break;
            default:
                return 2;
                break;
        }
    }
}
