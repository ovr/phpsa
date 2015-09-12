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

    public function visitPhpFunctionCall(FuncCall $funcCall/**, Context $context*/)
    {
        $name = $funcCall->name->getFirst();
        if (isset($this->map[$name])) {
            //@todo soon!
            return true;
        }

        return false;
    }
}

