<?php
/**
 * PHP Static Analysis project 2015
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
        $expression = new Expression($context);
        $left = $expression->compile($expr->left);

        $expression = new Expression($context);
        $right = $expression->compile($expr->right);

        switch ($left->getType()) {
            case CompiledExpression::INTEGER:
            case CompiledExpression::DOUBLE:
            case CompiledExpression::BOOLEAN:
            case CompiledExpression::ARR:
            case CompiledExpression::OBJECT:
                switch ($right->getType()) {
                    case CompiledExpression::INTEGER:
                    case CompiledExpression::DOUBLE:
                    case CompiledExpression::BOOLEAN:
                    case CompiledExpression::ARR:
                    case CompiledExpression::OBJECT:
                        return new CompiledExpression(CompiledExpression::BOOLEAN, $left->getValue() != $right->getValue());
                }
                break;
        }

        return new CompiledExpression(CompiledExpression::BOOLEAN);
    }
}
