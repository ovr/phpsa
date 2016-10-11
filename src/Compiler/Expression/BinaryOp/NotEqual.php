<?php
/**
 * PHP Smart Analysis project 2015-2016
 *
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Expression\BinaryOp;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class NotEqual extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\BinaryOp\NotEqual';

    /**
     * It's used in conditions
     * {left-expr} != {right-expr}
     *
     * @param \PhpParser\Node\Expr\BinaryOp\NotEqual $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $left = $context->getExpressionCompiler()->compile($expr->left);
        $right = $context->getExpressionCompiler()->compile($expr->right);

        if ($left->isTypeKnown() && $right->isTypeKnown()) {
            return CompiledExpression::fromZvalValue(
                $left->getValue() != $right->getValue()
            );
        }


        return new CompiledExpression();
    }
}
