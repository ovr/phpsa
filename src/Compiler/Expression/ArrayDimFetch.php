<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;

class ArrayDimFetch extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\ArrayDimFetch';

    /**
     * $array[1], $array[$var], $array["string"], "string"[1]
     *
     * @param \PhpParser\Node\Expr\ArrayDimFetch $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $compiler = $context->getExpressionCompiler();
        $var = $compiler->compile($expr->var);
        $dim = $compiler->compile($expr->dim);

        if ($var->getType() === CompiledExpression::UNIMPLEMENTED
         || $dim->getType() === CompiledExpression::UNIMPLEMENTED
        ) {
            return new CompiledExpression(CompiledExpression::UNIMPLEMENTED);
        }

        if (!$var->isTypeKnown() || !$dim->isTypeKnown()
         || !$var->isCorrectValue() || !$dim->isCorrectValue()
        ) {
            return new CompiledExpression();
        }

        switch ($var->getType()) {
            case CompiledExpression::STRING:
            case CompiledExpression::ARR:
                if ($dim->isArray()) {
                    $context->notice(
                        'language_error',
                        'Illegal offset type',
                        $expr
                    );
                    return new CompiledExpression();
                }
                break;
            default:
                break;
        }

        $resultArray = $var->getValue();
        return CompiledExpression::fromZvalValue($resultArray[$dim->getValue()]);
    }
}
