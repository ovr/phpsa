<?php

/**
 * @author Medvedev Alexandr https://github.com/lexty <alexandr.mdr@gmail.com>
 */
namespace Tests\Analyze\Fixtures\Statement;

function OptionalParamBeforeRequiredFunction($a = 1, $b)
{}

class OptionalParamBeforeRequiredClass
{
    public function OptionalParamBeforeRequiredMethod($a = 1, $b)
    {}
};

function OptionalParamAfterRequiredFunction($a, $b = 1)
{}

class OptionalParamAfterRequiredClass
{
    public function OptionalParamAfterRequiredMethod($a, $b = 1)
    {}
}

?>
----------------------------
PHPSA\Analyzer\Pass\Statement\OptionalParamBeforeRequired
----------------------------
[
    {
        "type":"optional-param-before-required",
        "message":"Optional parameter before required one is always required.",
        "file":"OptionalParamBeforeRequired.php",
        "line":7
    },
    {
        "type":"optional-param-before-required",
        "message":"Optional parameter before required one is always required.",
        "file":"OptionalParamBeforeRequired.php",
        "line":12
    }
]
