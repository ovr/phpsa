<?php

namespace Tests\Analyze\Fixtures\Statement;

class PropertyDefinitionDefaultValue
{
    public $a = null;
    public $b;
}

?>
----------------------------
PHPSA\Analyzer\Pass\Statement\PropertyDefinitionDefaultValue
----------------------------
[
    {
        "type": "property_definition_default_value",
        "message": "null is default and is not needed.",
        "file": "PropertyDefinitionDefaultValue.php",
        "line": 6
    }
]