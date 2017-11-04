<?php
namespace Tests\Analyze\Fixtures\Expression\FunctionCall;


class FunctionStringFormater
{

    public function testFirstArgNotString()
    {
        return printf( ["Dinos", "1 milion"], "Animals like %s rules world %s years ago");
    }

    public function testPrintfValid()
    {
        return printf("But people were born %s years after big %s", ["2 milion", "apocalyps"]);
    }

    public function testPrintfInvalid()
    {
        return printf("Animals like %s rules world %s years ago", ["Dinos"]);
    }

    public function testFirstArgNotStringsprintf()
    {
        return sprintf( ["Dinos", "1 milion"], "Animals like %s rules world %s years ago");
    }

    public function testSprintfValid()
    {
        return sprintf("But people were born %s years after big %s", ["2 milion", "apocalyps"]);
    }

    public function testSprintfInvalidType()
    {
        return sprintf("But people were born %k years after big %s", ["2 milion", "apocalyps"]);
    }

    public function testsPrintfInvalid()
    {
        return sprintf("Animals like %s rules world %s years ago", ["Dinos"]);
    }

    public function testsPrintfValidParamsLength()
    {
        return sprintf("Animals like %s rules world %s years ago", "dinos", 140);
    }

    public function testsPrintfInvalidParamsLength()
    {
        return sprintf("Animals like %s rules world %s years ago", "dinos");
    }
}



?>
----------------------------
PHPSA\Analyzer\Pass\Expression\FunctionCall\FunctionStringFormater
----------------------------
[
    {
        "type":"function_argument_invalid",
        "message":"First parameter of printf must be string",
        "file":"FunctionStringFormater.php",
        "line":9
    },
    {
        "type":"function_array_length_invalid",
        "message":"Unexpected length of array passed to printf",
        "file":"FunctionStringFormater.php",
        "line":19
    },
    {
        "type":"function_argument_invalid",
        "message":"First parameter of sprintf must be string",
        "file":"FunctionStringFormater.php",
        "line":24
    },
    {
        "type":"function_format_type_invalid",
        "message":"Unexpected type format in sprintf function string",
        "file":"FunctionStringFormater.php",
        "line":34
    },
    {
        "type":"function_array_length_invalid",
        "message":"Unexpected length of array passed to sprintf",
        "file":"FunctionStringFormater.php",
        "line":39
    },
    {
        "type":"function_arguments_length_invalid",
        "message":"Unexpected length of arguments passed to sprintf",
        "file":"FunctionStringFormater.php",
        "line":49
    }
]
