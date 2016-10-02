<?php

namespace PHPSA\Compiler\Expression;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class Ternary extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\Ternary';

    /**
     * ({expr}) ? {expr} : {expr}
     *
     * @param \PhpParser\Node\Expr\Ternary $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $condition = $context->getExpressionCompiler()->compile($expr->cond);
        $left = $context->getExpressionCompiler()->compile($expr->if);
        $right = $context->getExpressionCompiler()->compile($expr->else);

        if ($condition->getValue() == true) {
            return CompiledExpression::fromZvalValue($left->getValue());
        } else {
            return CompiledExpression::fromZvalValue($right->getValue());
        }
    }
}
