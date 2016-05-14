<?php
/**
 * PHP Smart Analysis project 2015-2016
 *
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Expression\Operators\Arithmetical;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class Minus extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\BinaryOp\Minus';

    /**
     * {expr} - {expr}
     *
     * @param \PhpParser\Node\Expr\BinaryOp\Minus $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $left = $context->getExpressionCompiler()->compile($expr->left);
        $right = $context->getExpressionCompiler()->compile($expr->right);

        switch ($left->getType()) {
            case CompiledExpression::INTEGER:
                switch ($right->getType()) {
                    case CompiledExpression::INTEGER:
                        return new CompiledExpression(CompiledExpression::INTEGER, $left->getValue() - $right->getValue());
                    case CompiledExpression::DOUBLE:
                        return new CompiledExpression(CompiledExpression::DOUBLE, $left->getValue() - $right->getValue());
                }
                break;
            case CompiledExpression::DOUBLE:
                switch ($right->getType()) {
                    case CompiledExpression::INTEGER:
                    case CompiledExpression::DOUBLE:
                        return new CompiledExpression(CompiledExpression::DOUBLE, $left->getValue() - $right->getValue());
                }
                break;
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }
}
