<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\FunctionCall;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PHPSA\Compiler\Expression;
use PHPSA\Context;

class DebugCode implements PassFunctionCallInterface
{
    protected $map = array(
        'var_dump' => 'var_dump',
        'var_export' => 'var_export',
        'debug_zval_dump' => 'debug_zval_dump'
    );

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

        if (isset($this->map[$name])) {
            if ($funcCall->getDocComment()) {
                $phpdoc = new \phpDocumentor\Reflection\DocBlock($funcCall->getDocComment()->getText());
                if ($phpdoc->hasTag('expected')) {
                    return true;
                }
            }

            $context->notice(
                'debug.code',
                sprintf('Function %s() is a debug code, please don`t use it in production.', $name),
                $funcCall
            );
        }
    }
}
