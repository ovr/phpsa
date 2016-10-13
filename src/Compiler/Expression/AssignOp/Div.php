<?php

namespace PHPSA\Compiler\Expression\AssignOp;

use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;
use PHPSA\Context;

class Div extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\AssignOp\Div';

    /**
     * {left-expr} /= {right-expr}
     *
     * @param \PhpParser\Node\Expr\AssignOp\Div $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $left = $context->getExpressionCompiler()->compile($expr->var);
        $expExpression = $context->getExpressionCompiler()->compile($expr->expr);

        if ($expExpression->isEquals(0)) {
            $context->notice(
                'language-error',
                'You are trying to divide by 0.',
                $expr
            );

            return new CompiledExpression();
        }

        switch ($left->getType()) {
            case CompiledExpression::INTEGER:
            case CompiledExpression::DOUBLE:
            case CompiledExpression::NUMBER:
            case CompiledExpression::BOOLEAN:
                switch ($expExpression->getType()) {
                    case CompiledExpression::INTEGER:
                    case CompiledExpression::DOUBLE:
                    case CompiledExpression::NUMBER:
                    case CompiledExpression::BOOLEAN:
                        return CompiledExpression::fromZvalValue(
                            $left->getValue() / $expExpression->getValue()
                        );
                }
        }
        
        return new CompiledExpression();
    }
}
