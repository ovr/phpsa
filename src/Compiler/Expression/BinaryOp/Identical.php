<?php

namespace PHPSA\Compiler\Expression\BinaryOp;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class Identical extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\BinaryOp\Identical';

    /**
     * It's used in conditions
     * {left-expr} === {right-expr}
     *
     * @param \PhpParser\Node\Expr\BinaryOp\Identical $expr
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
            case CompiledExpression::NUMBER:
            case CompiledExpression::NULL:
                switch ($right->getType()) {
                    case CompiledExpression::LNUMBER:
                    case CompiledExpression::DNUMBER:
                    case CompiledExpression::BOOLEAN:
                    case CompiledExpression::NUMBER:
                    case CompiledExpression::NULL:
                        return new CompiledExpression(CompiledExpression::BOOLEAN, $left->getValue() === $right->getValue());
                }
        }

        return new CompiledExpression(CompiledExpression::UNKNOWN);
    }
}
