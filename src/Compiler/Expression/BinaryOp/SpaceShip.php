<?php
/**
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Expression\BinaryOp;

use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;
use PHPSA\Context;

class SpaceShip extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\BinaryOp\Spaceship';

    /**
     * It's used in conditions
     * {left-expr} <=> {right-expr}
     *
     * @param \PhpParser\Node\Expr\BinaryOp\Spaceship $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $left = $context->getExpressionCompiler()->compile($expr->left);
        $right = $context->getExpressionCompiler()->compile($expr->right);

        if ($left->isTypeKnown() && $right->isTypeKnown()) {
            if ($left->getValue() == $right->getValue()) {
                return new CompiledExpression(CompiledExpression::INTEGER, 0);
            } elseif ($left->getValue() < $right->getValue()) {
                return new CompiledExpression(CompiledExpression::INTEGER, -1);
            } elseif ($left->getValue() > $right->getValue()) {
                return new CompiledExpression(CompiledExpression::INTEGER, 1);
            }
        }

        return new CompiledExpression();
    }
}
