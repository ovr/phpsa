<?php

namespace PHPSA\Analyze\Pass\FunctionCall;

use PhpParser\Node\Expr\FuncCall;
use PHPSA\Context;

class RandomApiMigration
{
    protected $map = array(
        'rand' => 'mt_rand',
        'srand' => 'mt_srand',
        'getrandmax' => 'mt_getrandmax'
    );

    public function visitPhpFunctionCall(FuncCall $funcCall, Context $context)
    {
        if (in_array($funcCall->name, $this->map, false)) {

        }
    }
}

