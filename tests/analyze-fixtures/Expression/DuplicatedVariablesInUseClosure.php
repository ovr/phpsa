<?php

namespace Tests\Analyze\Fixtures\Expression;

class DuplicatedVariablesInUseClosure
{
    /**
     * @return string
     */
    public function useValid()
    {
        function () use ($a, $b, $c) {

        };
    }

    /**
     * @return string
     */
    public function useInvalid()
    {
        function () use ($a, $b, $c, $a) {

        };
    }

}

?>
----------------------------
PHPSA\Analyzer\Pass\Expression\DuplicatedVariablesInUseClosure
----------------------------
[
    {
        "type":"duplicated_variable_in_use_closure",
        "message":"Duplicated variable $a in use statement.",
        "file":"DuplicatedVariablesInUseClosure.php",
        "line":21
    }
]
