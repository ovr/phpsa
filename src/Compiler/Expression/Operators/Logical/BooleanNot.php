<?php

namespace PHPSA\Compiler\Expression\Operators\Logical;

use PHPSA\CompiledExpression;
use PHPSA\Context;
use PHPSA\Compiler\Expression;
use PHPSA\Compiler\Expression\AbstractExpressionCompiler;

class BooleanNot extends AbstractExpressionCompiler
{
    protected $name = 'PhpParser\Node\Expr\BooleanNot';

    /**
     * !{expr}
     *
     * @param \PhpParser\Node\Expr\BooleanNot $expr
     * @param Context $context
     * @return CompiledExpression
     */
    protected function compile($expr, Context $context)
    {
        $compiledExpression = $context->getExpressionCompiler()->compile($expr->expr);

        switch ($compiledExpression->getType()) {
            case CompiledExpression::DOUBLE:
            case CompiledExpression::INTEGER:
            case CompiledExpression::NUMBER:
            case CompiledExpression::STRING:
            case CompiledExpression::BOOLEAN:
            case CompiledExpression::NULL:
            case CompiledExpression::ARR:
            case CompiledExpression::OBJECT:
                return CompiledExpression::fromZvalValue(
                    !$compiledExpression->getValue()
                );
        }

        return new CompiledExpression();
    }
}
