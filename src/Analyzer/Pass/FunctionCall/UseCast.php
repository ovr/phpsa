<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Analyzer\Pass\FunctionCall;

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PHPSA\Compiler\Expression;
use PHPSA\Context;

class UseCast implements PassFunctionCallInterface
{
    protected $map = array(
        'boolval' => 'bool',
        'intval' => 'int',
        'floatval' => 'double',
        'doubleval' => 'double',
        'strval' => 'string'
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
            /**
             * Exclusion via intval with 2 args intval($number, int $base = 10);
             */
            if ($name == 'intval' && count($funcCall->args) > 1) {
                return;
            }

            $context->notice(
                'fcall.cast',
                sprintf('Please use (%s) cast instead of function call.', $this->map[$name]),
                $funcCall
            );
        }
    }
}
