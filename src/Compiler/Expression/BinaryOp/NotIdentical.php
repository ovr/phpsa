<?php

namespace PHPSA\Compiler\Expression\BinaryOp;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class NotIdentical extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\BinaryOp\NotIdentical';

    /**
     * It's used in conditions
     * {left-expr} !== {right-expr}
     *
     * @param \PhpParser\Node\Expr\BinaryOp\NotIdentical $expr
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
                switch ($right->getType()) {
                    case CompiledExpression::INTEGER:
                    case CompiledExpression::DOUBLE:
                    case CompiledExpression::BOOLEAN:
                    case CompiledExpression::NUMBER:
                    case CompiledExpression::NULL:
                        return new CompiledExpression(CompiledExpression::BOOLEAN, $left->getValue() !== $right->getValue());
                }
        }

        return new CompiledExpression(CompiledExpression::BOOLEAN);
    }
}
