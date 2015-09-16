<?php

namespace PHPSA\Compiler\Expression\Operators\Bitwise;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class ShiftLeft extends AbstractExpressionCompiler
{
    protected $name = '\PhpParser\Node\Expr\BinaryOp\ShiftLeft';

    /**
     * {expr} << {expr}
     *
     * @param \PhpParser\Node\Expr\BinaryOp\ShiftLeft $expr
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
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
            case CompiledExpression::BOOLEAN:
                switch ($right->getType()) {
                    case CompiledExpression::LNUMBER:
                    case CompiledExpression::DNUMBER:
                    case CompiledExpression::BOOLEAN:
                        return new CompiledExpression(CompiledExpression::INTEGER, $left->getValue() << $right->getValue());
                }
                break;
        }

        return new CompiledExpression();
    }
}
