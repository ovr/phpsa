<?php

namespace PHPSA\Compiler\Expression\Operators\Logical;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class BooleanOr extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\BinaryOp\BooleanOr';

    /**
     * {expr} || {expr}
     *
     * @param \PhpParser\Node\Expr\BinaryOp\BooleanOr $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $left = $context->getExpressionCompiler()->compile($expr->left);
        $right = $context->getExpressionCompiler()->compile($expr->right);

        if ($left->isTypeKnown() && $right->isTypeKnown()) {
            return CompiledExpression::fromZvalValue(
                $left->getValue() || $right->getValue()
            );
        }

        return new CompiledExpression();
    }
}
