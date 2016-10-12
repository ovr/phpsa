<?php

namespace Tests\Analyze\Fixtures\Expression\FunctionCall;

class DeprecatedIniOptions
{
    public function testIniSet()
    {
        ini_set("asp_tags", "1");
    }

    public function testIniAlter()
    {
        ini_alter("mbstring.http_input", "pass");
    }

    public function testIniGet()
    {
        ini_get("highlight.bg");
    }

    public function testIniRestore()
    {
        ini_restore("safe_mode");
    }

    public function testOtherFunction()
    {
        htmlspecialchars("asp_tags");
    }
}
?>
----------------------------
PHPSA\Analyzer\Pass\Expression\FunctionCall\DeprecatedIniOptions
----------------------------
[
    {
        "type":"deprecated.option",
        "message":"Ini option asp_tags is a deprecated option since PHP 7.0.0.",
        "file":"DeprecatedIniOptions.php",
        "line":8
    },
    {
        "type":"deprecated.option",
        "message":"Ini option mbstring.http_input is a deprecated option since PHP 5.6.0. Use 'default_charset' instead.",
        "file":"DeprecatedIniOptions.php",
        "line":13
    },
    {
        "type":"deprecated.option",
        "message":"Ini option highlight.bg is a deprecated option since PHP 5.4.0.",
        "file":"DeprecatedIniOptions.php",
        "line":18
    },
    {
        "type":"deprecated.option",
        "message":"Ini option safe_mode is a deprecated option since PHP 5.3.0 (removed in PHP 5.4.0).",
        "file":"DeprecatedIniOptions.php",
        "line":23
    }
]
