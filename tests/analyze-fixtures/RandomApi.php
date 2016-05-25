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

?>
----------------------------
[
    {
        "type":"rand.api.migration",
        "message":"Function rand() is not recommended, please use random_int/random_bytes (PHP 7) or mt_rand (not cryptographically secure) instead.",
        "file":"RandomApi.php",
        "line":11
    },
    {
        "type":"rand.api.migration",
        "message":"Function srand() is not recommended, please use random_int/random_bytes (PHP 7) or mt_srand (not cryptographically secure) instead.",
        "file":"RandomApi.php",
        "line":19
    },
    {
        "type":"rand.api.migration",
        "message":"Function getrandmax() is not recommended, please use random_int/random_bytes (PHP 7) or mt_getrandmax (not cryptographically secure) instead.",
        "file":"RandomApi.php",
        "line":27
    }
]
