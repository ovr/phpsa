<?php

namespace Tests\Analyze\Fixtures\Expression;

class ArrayIllegalOffsetType
{
    /**
     * @return array
     */
    public function arrayDeclarationWithObject()
    {
        return [
            'foo' => 'bar',
            new \stdClass => 'biz',
        ];
    }

    /**
     * @return array
     */
    public function arrayDeclarationWithAVariableContainingAnObject()
    {
        $variable = new \DateTime();

        return [
            0 => 42,
            $variable => 43,
        ];
    }

    /**
     * @return array
     */
    public function arrayAssignationWithObject()
    {
        $array = [];

        $array[new \DateTime()] = 'foo';
        $array[] = 'bar';

        return $array;
    }

    /**
     * @return array
     */
    public function arrayAssignationWithAVariableContainingAnObject()
    {
        $variable = new \DateTime();

        $array = [];
        $array[$variable] = 'foo';

        return $array;
    }

    /**
     * @return array
     */
    public function validArray()
    {
        return [
            '42' => 'another truth'
        ];
    }

    /**
     * @return array
     */
    public function arrayPropertyDeclarationWithObject()
    {
        $this->foo = [
            'foo' => 'bar',
            new \stdClass => 'biz',
        ];
    }

    /**
     * @return array
     */
    public function arrayPropertyDeclarationWithAVariableContainingAnObject()
    {
        $variable = new \DateTime();

        $this->foo = [
            0 => 42,
            $variable => 43,
        ];
    }

    /**
     * @return array
     */
    public function arrayPropertyAssignationWithObject()
    {
        $this->array = [];

        $this->array[new \DateTime()] = 'foo';
        $this->array[] = 'bar';
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Expression\ArrayIllegalOffsetType
----------------------------
[
    {
        "type":"array.illegal_offset_type",
        "message":"Illegal array offset type stdClass.",
        "file":"ArrayIllegalOffsetType.php",
        "line":13
    },
    {
        "type":"array.illegal_offset_type",
        "message":"Illegal array offset type DateTime for key $variable.",
        "file":"ArrayIllegalOffsetType.php",
        "line":26
    },
    {
        "type":"array.illegal_offset_type",
        "message":"Illegal array offset type DateTime.",
        "file":"ArrayIllegalOffsetType.php",
        "line":37
    },
    {
        "type":"array.illegal_offset_type",
        "message":"Illegal array offset type DateTime for key $variable.",
        "file":"ArrayIllegalOffsetType.php",
        "line":51
    },
    {
        "type":"array.illegal_offset_type",
        "message":"Illegal array offset type stdClass.",
        "file":"ArrayIllegalOffsetType.php",
        "line": 73
    },
    {
        "type":"array.illegal_offset_type",
        "message":"Illegal array offset type DateTime for key $variable.",
        "file":"ArrayIllegalOffsetType.php",
        "line":86
    },
    {
        "type":"array.illegal_offset_type",
        "message":"Illegal array offset type DateTime.",
        "file":"ArrayIllegalOffsetType.php",
        "line":97
    }
]
