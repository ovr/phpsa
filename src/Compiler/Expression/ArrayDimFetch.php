<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class ArrayDimFetch extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\ArrayDimFetch';

    /**
     * $array[1], $array[$var], $array["string"]
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

        if (!$var->isArray()) {
            $context->notice(
                'array_dim_fetch_on_non_array',
                "It's not possible to fetch an array element on a non array",
                $expr
            );
            
            return new CompiledExpression();
        }

        if (!in_array($dim->getValue(), $var->getValue())) {
            $context->notice(
                'array_dim_fetch_not_found',
                "The array does not contain this value",
                $expr
            );

            return new CompiledExpression();
        }

        $resultArray = $var->getValue();

        return CompiledExpression::fromZvalValue($resultArray[$dim->getValue()]);
    }
}
