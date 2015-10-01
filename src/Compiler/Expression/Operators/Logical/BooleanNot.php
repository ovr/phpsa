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
        $expression = new Expression($context);
        $compiledExpression = $expression->compile($expr->expr);

        switch ($compiledExpression->getType()) {
            case CompiledExpression::DNUMBER:
            case CompiledExpression::LNUMBER:
            case CompiledExpression::STRING:
            case CompiledExpression::BOOLEAN:
                return new CompiledExpression(CompiledExpression::BOOLEAN, !$compiledExpression->getValue());
        }


        return new CompiledExpression();
    }
}
