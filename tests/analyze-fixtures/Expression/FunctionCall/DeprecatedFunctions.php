<?php

namespace Tests\Analyze\Fixtures\Expression\FunctionCall;

class DeprecatedFunctions
{
    /**
     * @return array
     */
    public function testSplit()
    {
        return split(":","a:b");
    }

    /**
     * @return string
     */
    public function testMysqlRealEscapeString()
    {
        return mysql_real_escape_string("abc");
    }
    
    /**
     * @return string
     */
    public function testMcryptCreateIv()
    {
        return mcrypt_create_iv(60, MCRYPT_RAND);
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Expression\FunctionCall\DeprecatedFunctions
----------------------------
[
    {
        "type":"deprecated.function",
        "message":"split() is deprecated since PHP 5.3. Use explode() instead.",
        "file":"DeprecatedFunctions.php",
        "line":11
    },
    {
        "type":"deprecated.function",
        "message":"The MySQL Extension is deprecated since PHP 5.5. Use PDO instead.",
        "file":"DeprecatedFunctions.php",
        "line":19
    },
    {
        "type":"deprecated.function",
        "message":"The Mcrypt Extension is deprecated since PHP 7.1. Use paragonie/halite instead.",
        "file":"DeprecatedFunctions.php",
        "line":27
    }
]
