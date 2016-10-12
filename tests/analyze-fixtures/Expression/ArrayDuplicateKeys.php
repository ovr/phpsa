<?php

namespace Tests\Analyze\Fixtures\Expression;

class ArrayDuplicateKeys
{
    /**
     * @return array
     */
    public function arrayWithDuplicateKey()
    {
        return [
            'foo' => 'bar',
            'baz' => 'biz',
            'foo' => 'joe',
        ];
    }

    /**
     * @return array
     */
    public function arrayWithDuplicateNumericKey()
    {
        return [
            0 => 42,
            1 => 43,
            0 => 'lala',
        ];
    }

    /**
     * @return array
     */
    public function arrayWithDuplicateVariableKey()
    {
        $zero = 0;

        return [
            0 => 42,
            1 => 43,
            $zero => 'lala',
        ];
    }

    /**
     * @return array
     */
    public function validArray()
    {
        return [
            'foo' => 'bar',
            'baz' => 'biz',
            0 => 42,
            1 => 43,
        ];
    }
}
?>
----------------------------
\PHPSA\Analyzer\Pass\Expression\ArrayDuplicateKeys
----------------------------
[
    {
        "type":"array.duplicate_keys",
        "message":"Duplicate array key \"foo\" in array definition (previously declared in line 13).",
        "file":"ArrayDuplicateKeys.php",
        "line":14
    },
    {
        "type":"array.duplicate_keys",
        "message":"Duplicate array key \"0\" in array definition (previously declared in line 25).",
        "file":"ArrayDuplicateKeys.php",
        "line":26
    },
    {
        "type":"array.duplicate_keys",
        "message":"Duplicate array key \"$zero (resolved value: \"0\")\" in array definition (previously declared in line 39).",
        "file":"ArrayDuplicateKeys.php",
        "line":40
    }
]
