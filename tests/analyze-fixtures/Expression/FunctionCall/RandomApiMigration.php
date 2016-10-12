<?php

namespace Tests\Analyze\Fixtures\Expression\FunctionCall;

class RandomApiMigration
{
    /**
     * @return integer
     */
    public function testRand()
    {
        return rand(0, 100);
    }

    /**
     * @return integer
     */
    public function testSrand()
    {
        srand();
        return 1;
    }

    /**
     * @return integer
     */
    public function testGetRandMax()
    {
        return getrandmax();
    }
}

?>
----------------------------
PHPSA\Analyzer\Pass\Expression\FunctionCall\RandomApiMigration
----------------------------
[
    {
        "type":"rand.api.migration",
        "message":"Function rand() is not recommended, please use random_int/random_bytes (PHP 7) or mt_rand (not cryptographically secure) instead.",
        "file":"RandomApiMigration.php",
        "line":11
    },
    {
        "type":"rand.api.migration",
        "message":"Function srand() is not recommended, please use random_int/random_bytes (PHP 7) or mt_srand (not cryptographically secure) instead.",
        "file":"RandomApiMigration.php",
        "line":19
    },
    {
        "type":"rand.api.migration",
        "message":"Function getrandmax() is not recommended, please use random_int/random_bytes (PHP 7) or mt_getrandmax (not cryptographically secure) instead.",
        "file":"RandomApiMigration.php",
        "line":28
    }
]
