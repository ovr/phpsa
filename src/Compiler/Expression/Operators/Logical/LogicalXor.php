<?php

namespace PHPSA\Compiler\Expression\Operators\Logical;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class LogicalXor extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\BinaryOp\LogicalXor';

    /**
     * {expr} xor {expr}
     *
     * @param \PhpParser\Node\Expr\BinaryOp\LogicalXor $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $left = $context->getExpressionCompiler()->compile($expr->left);
        $right = $context->getExpressionCompiler()->compile($expr->right);

        if ($left->isTypeKnown() && $right->isTypeKnown()) {
            return CompiledExpression::fromZvalValue(
                $left->getValue() xor $right->getValue()
            );
        }

        return new CompiledExpression();
    }
}
