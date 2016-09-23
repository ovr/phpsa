<?php

namespace PHPSA\Compiler\Expression\AssignOp;

use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;
use PHPSA\Context;

class Concat extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\AssignOp\Concat';

    /**
     * {left-expr} .= {right-expr}
     *
     * @param \PhpParser\Node\Expr\AssignOp\Concat $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $left = $context->getExpressionCompiler()->compile($expr->var);
        $expExpression = $context->getExpressionCompiler()->compile($expr->expr);

        switch ($left->getType()) {
            case CompiledExpression::INTEGER:
            case CompiledExpression::DOUBLE:
            case CompiledExpression::NUMBER:
            case CompiledExpression::STRING:
            case CompiledExpression::BOOLEAN:
                switch ($expExpression->getType()) {
                    case CompiledExpression::INTEGER:
                    case CompiledExpression::DOUBLE:
                    case CompiledExpression::NUMBER:
                    case CompiledExpression::STRING:
                    case CompiledExpression::BOOLEAN:
                        return CompiledExpression::fromZvalValue(
                            $left->getValue() . $expExpression->getValue()
                        );
                }
        }

        return new CompiledExpression();
    }
}
