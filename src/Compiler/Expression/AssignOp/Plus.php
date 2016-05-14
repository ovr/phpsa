<?php
/**
 * PHP Smart Analysis project 2015-2016
 *
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Compiler\Expression\AssignOp;

use PHPSA\CompiledExpression;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;
use PHPSA\Context;

class Plus extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\AssignOp\Plus';

    /**
     * It's used in conditions
     * {left-expr} += {right-expr}
     *
     * @param \PhpParser\Node\Expr\AssignOp\Plus $expr
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
                switch ($expExpression->getType()) {
                    case CompiledExpression::INTEGER:
                    case CompiledExpression::DOUBLE:
                    case CompiledExpression::NUMBER:
                        return CompiledExpression::fromZvalValue(
                            $left->getValue() + $expExpression->getValue()
                        );
                        break;
                }
                break;
        }

        return $left;
    }
}
