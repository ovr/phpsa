<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\FunctionCall;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PHPSA\Context;

class ArrayShortDefinition implements PassFunctionCallInterface
{
    public function visitPhpFunctionCall(FuncCall $funcCall, Context $context)
    {
        $name = false;

        if ($funcCall->name instanceof Name && !$funcCall->name->isFullyQualified()) {
            $name = $funcCall->name->getFirst();
        }

        if ($name && $name == 'array') {
            $context->notice(
                'array.short-syntax',
                'Please use [] (short syntax) for array definition.',
                $funcCall
            );
        }
    }
}
