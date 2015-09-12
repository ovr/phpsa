<?php

namespace Sandbox;

use stdClass;

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
        $a = 1;
        $b = &$a;

        return $b;
    }
}
