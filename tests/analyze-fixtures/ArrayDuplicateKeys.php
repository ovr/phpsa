<?php

namespace Tests\Compiling\Statements;

class ArrayDuplicateKeys
{
    /**
     * @return array
     */
    public function method()
    {
        return [
            'foo' => 'bar',
            'baz' => 'biz',
            'foo' => 'joe',
            0 => 42,
            1 => 43,
            0 => 'lala'
        ];
    }
}
?>
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
        "message":"Duplicate array key \"0\" in array definition (previously declared in line 16).",
        "file":"ArrayDuplicateKeys.php",
        "line":17
    }
]
