<?php

namespace PHPSA\Compiler\Expression\Operators\Logical;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class BooleanAnd extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\BinaryOp\BooleanAnd';

    /**
     * {expr} && {expr}
     *
     * @param \PhpParser\Node\Expr\BinaryOp\BooleanAnd $expr
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
            case CompiledExpression::STRING:
            case CompiledExpression::BOOLEAN:
            case CompiledExpression::NULL:
                switch ($right->getType()) {
                    case CompiledExpression::INTEGER:
                    case CompiledExpression::DOUBLE:
                    case CompiledExpression::STRING:
                    case CompiledExpression::BOOLEAN:
                    case CompiledExpression::NULL:
                        return CompiledExpression::fromZvalValue($left->getValue() && $right->getValue());
                }
                break;
        }

        return new CompiledExpression(CompiledExpression::BOOLEAN);
    }
}
