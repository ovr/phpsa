<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\FunctionCall;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PHPSA\Compiler\Expression;
use PHPSA\Context;

class ArrayShortDefinition implements PassFunctionCallInterface
{
    public function visitPhpFunctionCall(FuncCall $funcCall, Context $context)
    {
        $compiler = new Expression($context);
        $funcNameCompiledExpression = $compiler->compile($funcCall->name);

        if ($funcNameCompiledExpression->isString() && $funcNameCompiledExpression->isCorrectValue()) {
            $name = $funcNameCompiledExpression->getValue();
        } else {
            $context->debug(
                'Unexpected function name type ' . $funcNameCompiledExpression->getType(),
                $funcCall->name
            );

            return false;
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
