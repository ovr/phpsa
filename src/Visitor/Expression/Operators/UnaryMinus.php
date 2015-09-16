<?php
/**
 * PHP Static Analysis project 2015
 *
 * @author Patsura Dmitry https://github.com/ovr <talk@dmtry.me>
 */

namespace PHPSA\Visitor\Expression\Operators;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Visitor\Expression;
use PHPSA\Visitor\Expression\AbstractExpressionCompiler;

class UnaryMinus extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\UnaryMinus';

    /**
     * -{expr}
     *
     * @param \PhpParser\Node\Expr\UnaryMinus $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $expression = new Expression($context);
        $left = $expression->compile($expr->expr);

        switch ($left->getType()) {
            case CompiledExpression::LNUMBER:
            case CompiledExpression::DNUMBER:
            case CompiledExpression::BOOLEAN:
            case CompiledExpression::STRING:
            case CompiledExpression::NULL:
                return new CompiledExpression(CompiledExpression::INTEGER, -$left->getValue());
            case CompiledExpression::ARR:
                $context->notice(
                    'unsupported-operand-types',
                    'Unsupported operand types -{array}',
                    $expr
                );
        }

        return new CompiledExpression();
    }
}
