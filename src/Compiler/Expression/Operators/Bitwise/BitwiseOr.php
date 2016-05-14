<?php

namespace PHPSA\Compiler\Expression\Operators\Bitwise;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class BitwiseOr extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\BinaryOp\BitwiseOr';

    /**
     * {expr} | {expr}
     *
     * @param \PhpParser\Node\Expr\BinaryOp\BitwiseOr $expr
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
                switch ($right->getType()) {
                    case CompiledExpression::INTEGER:
                    case CompiledExpression::DOUBLE:
                    case CompiledExpression::BOOLEAN:
                        return CompiledExpression::fromZvalValue($left->getValue() | $right->getValue());
                }
                break;
        }

        return new CompiledExpression();
    }
}
