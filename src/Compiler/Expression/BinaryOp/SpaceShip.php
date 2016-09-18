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

        switch ($left->getType()) {
            case CompiledExpression::INTEGER:
            case CompiledExpression::DOUBLE:
            case CompiledExpression::BOOLEAN:
            case CompiledExpression::NUMBER:
            case CompiledExpression::NULL:
            case CompiledExpression::ARR:
            case CompiledExpression::OBJECT:
            case CompiledExpression::STRING:
                switch ($right->getType()) {
                    case CompiledExpression::INTEGER:
                    case CompiledExpression::DOUBLE:
                    case CompiledExpression::BOOLEAN:
                    case CompiledExpression::NUMBER:
                    case CompiledExpression::NULL:
                    case CompiledExpression::ARR:
                    case CompiledExpression::OBJECT:
                    case CompiledExpression::STRING:
                        if ($left->getValue() == $right->getValue()) {
                            return new CompiledExpression(CompiledExpression::INTEGER, 0);
                        } elseif ($left->getValue() < $right->getValue()) {
                            return new CompiledExpression(CompiledExpression::INTEGER, -1);
                        } elseif ($left->getValue() > $right->getValue()) {
                            return new CompiledExpression(CompiledExpression::INTEGER, 1);
                        }
                }
        }

        return new CompiledExpression();
    }
}
