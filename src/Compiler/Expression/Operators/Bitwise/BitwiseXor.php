<?php

namespace PHPSA\Compiler\Expression\Operators\Bitwise;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class BitwiseXor extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\BinaryOp\BitwiseXor';

    /**
     * {expr} ^ {expr}
     *
     * @param \PhpParser\Node\Expr\BinaryOp\BitwiseXor $expr
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
                        return new CompiledExpression(CompiledExpression::DOUBLE, $left->getValue() ^ $right->getValue());
                }
                break;
        }

        return new CompiledExpression();
    }
}
